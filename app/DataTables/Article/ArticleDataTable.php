<?php

namespace App\DataTables\Article;

use App\Models\Article;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ArticleDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Article> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $buttons = [
            'show' => [
                'permission' => 'article.show',
                'class' => 'btn-info show-record',
                'icon' => 'fa-circle-info',
                'target' => '#show-record',
            ],
            'edit' => [
                'permission' => 'article.edit',
                'class' => 'btn-warning edit-record',
                'icon' => 'fa-pen-to-square',
                'target' => '#edit-record',
            ],
            'delete' => [
                'permission' => 'article.delete',
                'class' => 'btn-danger delete-record',
                'icon' => 'fa-trash',
                'target' => null,
            ],
        ];

        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) use ($buttons) {
                $actions = '';

                foreach ($buttons as $btn) {
                    if (canUserPerformAction($btn['permission'])) {
                        $actions .= sprintf(
                            '<button class="btn %s btn-sm me-1 mb-1" data-id="%s"%s><i class="fa-solid %s"></i></button>',
                            $btn['class'],
                            $query->id,
                            $btn['target'] ? ' data-target="' . $btn['target'] . '"' : '',
                            $btn['icon']
                        );
                    }
                }

                return $actions ?: '-';
            })
            ->addColumn('image_url', function ($query) {
                return $query->image ?? null;
            })
            ->addColumn('image', function ($query) {
                if (is_null($query->image)) {
                    return '<span>-</span>';
                }

                return '<a href="'.$query->image.'" class="d-block" data-lightbox="poster" data-title="'.$query->title.'">'
                    . '<img src="'.$query->image.'" alt="'.$query->slug.'" class="article-image img-table-lightbox" loading="lazy" height=150 />'
                    . '</a>';
            })
            ->editColumn('categories', function ($query) {
                return $query->categories->pluck('label')->map(fn($label) => "
                    <span class='badge rounded-pill bg-gradient bg-info text-black me-1 mb-1' style='font-size: 0.95em;'>
                        $label
                    </span>
                ")->implode(' ');
            })
            ->addColumn('categories_plain', function ($query) {
                return $query->categories->pluck('id')->toArray();
            })
            ->editColumn('categories_name', function ($query) {
                return $query->categories->pluck('label')->implode(', ');
            })
            ->editColumn('author', function ($query) {
                return $query->author->personal->full_name ?? 'Unknown Author';
            })
            ->addColumn('content', function ($query) {
                return html_entity_decode($query->content, ENT_QUOTES, 'UTF-8');
            })
            ->addColumn('content_button', function ($query) {
                return '<button class="btn btn-primary btn-sm view-content" data-id="' . $query->id . '">View Content</button>';
            })
            ->editColumn('excerpt', function ($query) {
                return str($query->excerpt)->limit(97, '...');
            })
            ->rawColumns(['action', 'image', 'categories', 'content_button', 'content'])
            ->addIndexColumn();
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Article>
     */
    public function query(Article $model): QueryBuilder
    {
        $query = $model
            ->select('id', 'title', 'slug', 'image', 'excerpt', 'content', 'author_id', 'created_at', 'updated_at')
            ->with('categories', 'author:id', 'author.personal:id,user_id,first_name,last_name');

        return isUserHasRole(config('rbac.role.highest')) ? $query : $query->where('author_id', auth('web')->id());
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('article-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'desc')
            ->lengthChange(true)
            ->pageLength(10)
            ->autoWidth(true)
            ->responsive(true)
            ->selectStyleSingle()
            ->layout([
                'top1Start' => "buttons",
                'bottomStart' => "info",
                'bottomEnd' => "paging",
            ])
            ->buttons([
                Button::make('export'),
                Button::make('print'),
                Button::make('reload'),
                Button::make('copy'),
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('updated_at')
                ->exportable(false)
                ->orderable(true)
                ->printable(false)
                ->addClass('text-center')
                ->hidden(),
            Column::computed('DT_RowIndex')
                ->title('No')
                ->addClass('text-center'),
            Column::make('title')
                ->title('Judul')
                ->addClass('text-center'),
            Column::computed('image_url')
                ->title('URL Gambar')
                ->exportable(true)
                ->printable(false)
                ->hidden(),
            Column::computed('image')
                ->title('Gambar')
                ->exportable(false)
                ->printable(true)
                ->addClass('text-center'),
            Column::make('excerpt')
                ->title('Kutipan')
                ->addClass('text-center'),
            Column::make('slug')
                ->title('Slug')
                ->addClass('text-center')
                ->hidden(),
            Column::computed('author')
                ->sortable(false)
                ->title('Penulis')
                ->addClass('text-center'),
            Column::computed('categories')
                ->sortable(false)
                ->title('Label Kategori')
                ->addClass('text-center')
                ->exportable(false),
            Column::computed('categories_name')
                ->title('Kategori (Label)')
                ->exportable(true)
                ->printable(false)
                ->hidden(),
            Column::make('content_button')
                ->title('Konten')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center'),
            Column::computed('action')
                ->title('Action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Article_' . date('YmdHis');
    }
}

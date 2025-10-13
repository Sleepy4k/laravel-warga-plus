<?php

namespace App\DataTables\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProductDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $buttons = [
            'show' => [
                'permission' => 'product.show',
                'class' => 'btn-info show-record',
                'icon' => 'fa-circle-info',
                'target' => '#show-record',
            ],
            'edit' => [
                'permission' => 'product.edit',
                'class' => 'btn-warning edit-record',
                'icon' => 'fa-pen-to-square',
                'target' => '#edit-record',
            ],
            'delete' => [
                'permission' => 'product.delete',
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
            ->addColumn('image', function ($query) {
                if (is_null($query->detail->image_url)) {
                    return '<span>-</span>';
                }

                return '<a href="'.$query->detail->image_url.'" class="d-block" data-lightbox="poster" data-title="'.$query->name.'">'
                    . '<img src="'.$query->detail->image_url.'" alt="'.$query->name.'" class="product-image img-table-lightbox" loading="lazy" height=150 />'
                    . '</a>';
            })
            ->editColumn('detail.is_available', function ($query) {
                return $query->detail->is_available ? 'Ya' : 'Tidak';
            })
            ->rawColumns(['action', 'image'])
            ->addIndexColumn();
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Product $model): QueryBuilder
    {
        $query = $model
            ->select('products.id', 'products.name', 'description', 'category_id', 'detail_id', 'user_id', 'products.created_at', 'products.updated_at')
            ->with('detail:id,price,rating,is_available,location,image_url', 'category:id,label');

        return isUserHasRole(config('rbac.role.highest')) ? $query : $query->where('user_id', auth('web')->id());
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('product-table')
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
            Column::make('name')
                ->title('Nama')
                ->addClass('text-center'),
            Column::computed('image')
                ->exportable(false)
                ->title('Foto')
                ->addClass('text-center'),
            Column::computed('detail.image_url')
                ->printable(false)
                ->title('Link Foto')
                ->addClass('text-center')
                ->hidden(),
            Column::make('description')
                ->title('Deskripsi')
                ->addClass('text-center'),
            Column::make('detail.price')
                ->title('Harga')
                ->addClass('text-center'),
            Column::make('detail.rating')
                ->title('Rating')
                ->addClass('text-center'),
            Column::make('detail.is_available')
                ->title('Tersedia')
                ->addClass('text-center'),
            Column::make('category.label')
                ->title('Categori')
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
        return 'Product_' . date('YmdHis');
    }
}

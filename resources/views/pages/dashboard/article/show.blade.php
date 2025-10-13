<x-layouts.dashboard title="Article Preview">
    @pushOnce('plugin-styles')
        <link rel="stylesheet" href="{{ asset('vendor/css/pages/page-help-center.css') }}" @cspNonce />
    @endPushOnce

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-0">
                <span class="text-muted fw-light">Dashboard / Article / </span>Preview
            </h4>
        </div>

        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <a class="btn btn-label-primary mb-4" href="{{ route('dashboard.article.index') }}">
                            <i class="bx bx-chevron-left scaleX-n1-rtl me-1"></i>
                            <span class="align-middle">Back</span>
                        </a>
                        <h4 class="d-flex align-items-center mt-2 mb-4">
                            <span class="badge bg-label-secondary p-2 rounded me-3">
                                <i class="bx bx-news bx-sm"></i>
                            </span>
                            {{ $article->title }}
                        </h4>
                        {!! html_entity_decode($article->content) !!}
                        <hr class="container-m-nx" />
                        <div class="d-flex justify-content-between flex-wrap gap-3 mb-2">
                            <div class="article-info">
                                <h5 class="mb-1">Did you find this article helpful?</h5>
                                <p class="card-text">{{ rand(30, 100) }} People found this helpful</p>
                            </div>
                            <h5>Still need help? <a href="javascript:void(0);">Contact us</a></h5>
                        </div>
                        <div class="article-votes">
                            <a href="javascript:void(0);" class="badge bg-label-primary me-2"><i
                                    class="bx bx-like"></i></a>
                            <a href="javascript:void(0);" class="badge bg-label-primary"><i
                                    class="bx bx-dislike"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>

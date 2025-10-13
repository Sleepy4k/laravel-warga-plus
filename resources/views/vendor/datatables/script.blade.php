$(function() {
    var namespace = "{{ config('datatables-html.namespace', 'LaravelDataTables') }}";
    window[namespace] = window[namespace] || {};
    window[namespace]["%1$s"] = $("#%1$s").DataTable(%2$s);
});
@foreach ($scripts as $script)
    @include($script)
@endforeach

<footer class="footer rafa-workspace-footer">
    <div class="container-fluid">
        <a class="rafa-footer-brand" href="{{ route('index') }}">RAFAIMMOBILIER</a>
        <div class="rafa-footer-copyright">
            {!! !is_null($footerTextInfo) ? $footerTextInfo->copyright_text : '' !!}
        </div>
        <button type="button" class="rafa-footer-top" data-rafa-scroll-top>
            {{ __('Back to top') }} <i class="fas fa-arrow-up" aria-hidden="true"></i>
        </button>
    </div>
</footer>

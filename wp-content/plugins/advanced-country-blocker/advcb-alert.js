(function () {
    if (typeof advcb_redirect !== 'undefined' && advcb_redirect.url) {
        alert('You have successfully gained temporary access for 60 minutes.');
        window.location.href = advcb_redirect.url;
    }
})();

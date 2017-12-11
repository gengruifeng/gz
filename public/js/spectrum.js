$(document).ready(function() {

  // the basics



  // remote
  // ------

  var bestPictures = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('subject'),
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
      url: '/search?q=%QUERY',
      wildcard: '%QUERY'
    }
  });

  $('#remote .typeahead').typeahead(null, {
    name: 'best-pictures',
    display: 'subject',
    source: bestPictures
  });


});

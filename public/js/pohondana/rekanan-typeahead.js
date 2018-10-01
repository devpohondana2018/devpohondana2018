$(document).ready(() => {
       $('#company_id').typeahead({
  hint: true,
  highlight: true,
  minLength: 0
},
{
  async: true,
  source: function (query, processSync, processAsync) {
    // processSync(['This suggestion appears immediately', 'This one too']);
    return $.ajax({
      url: path, 
      type: 'GET',
      data: {query: query},
      dataType: 'json',
      success: function (json) {
        console.log(json);
        // in this example, json is simply an array of strings
        var data = [];
        for(var i = 0; i < json.length; i++){
          data.push(json[i].name);
        }
        return processAsync(data);
      }
    });
  },
  limit: 100,
});
    });

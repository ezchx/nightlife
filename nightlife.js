$(document).ready(function() {

  var going = [];

  $(".butty").click(function(){
    search();
  });
  
  $('#location').bind('keypress', function(e) {
    if(e.keyCode==13){
      $('#location').blur();
	  search();
    }
  });


  if (document.cookie.indexOf("ezchxNightlife") >= 0) {
    $(".login").html('<button type="button" class="btn-sm btn-danger" id="logout">Logout</button>');
  } else {
    $(".login").html('<a href="index.php?login=yes"><img src="http://ezchx.com/twitteroauth/sign-in-with-twitter-gray.png" /></a>');
  }


  $("#logout").click(function(){
    //$("#debug2").html("hi");
    document.cookie = "ezchxNightlife=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
    //localStorage.removeItem("ezchxNightlife");
    $(".login").html('<a href="index.php?login=yes"><img src="http://ezchx.com/twitteroauth/sign-in-with-twitter-gray.png" /></a>');
  });


  $(document).on("click", ".updateGoing", function() {

  if (document.cookie.indexOf("ezchxNightlife") >= 0) {
    $.ajax({
      url: 'nightlife.php',
      type: 'POST',
      dataType: 'json',
      data: ({func: "update", locationID: this.id }),
      success: function(data){
        search();
      }
    });
    //$("#debug2").html(this.id);
  } else {
    window.location.href = "index.php?login=yes";
  }

  });


  function search() {
    
    var html = "";
    //$("#search_results").html(html);
    var location = $("#location").val();
    if (location.length != 0) {localStorage.setItem("ezchxNightlifeLocation", location);}
    if (location.length === 0) {
      //$("#search_results").html("");
      var location = localStorage.getItem("ezchxNightlifeLocation");
    }

    // download database
    $.ajax({
      url: 'nightlife.php',
      type: 'POST',
      dataType: 'json',
      data: ({ func: "download" }),
      success: function(going){

        // search yelp
        url = "http://ezchx.com/yelpoauth/sample.php?location=" + location;
        $.getJSON(url, function(json) {
          for (var i = 0; i < json.businesses.length; i++) {
        
            if (json.businesses[i].location.neighborhoods) {
              var neighby = json.businesses[i].location.neighborhoods;
            } else {
              var neighby = " ";
            }

            if (json.businesses[i].name.length > 35) {json.businesses[i].name = json.businesses[i].name.substring(0, 35);}

            var numGoing = 0;
            for (var j = 0; j < going.length; j++) {
              if (going[j].locationID === json.businesses[i].id) {numGoing += 1;}
            }
      
            html += '    <div class="container col-md-offset-3">';
            html += '      <div class="row pad">';
            html += '        <div class="col-md-2">';
            html += '         <div class="quad_line"><a href="' + json.businesses[i].url + '" target="_blank"><img src=' + json.businesses[i].image_url + ' height="140"></a></div>';
            html += '        </div>';
            html += '        <div class="col-md-6">';
            html += '          <div class="row">';
            html += '            <div class="col-md-6 name"><a href="' + json.businesses[i].url + '" target="_blank">' + json.businesses[i].name + '</a></div>';
            html += '            <div class="col-md-6 going"><button type="button" class="btn-xs btn-success updateGoing" id="' + json.businesses[i].id + '">' + numGoing + ' Going</button></div>';
            html += '            <div class="col-md-6 stars">' + json.businesses[i].rating + ' star rating</div>';
            html += '            <div class="col-md-6 street">' + json.businesses[i].location.address + '</div>';
            html += '            <div class="col-md-6 count">83 reviews</div>';
            html += '            <div class="col-md-6 city">' + json.businesses[i].location.city + ', ' + json.businesses[i].location.state_code + ' ' + json.businesses[i].location.postal_code + '</div>';
            html += '            <div class="col-md-6 spacey neighborhood">' + neighby + '</div>';
            html += '            <div class="col-md-6 spacey phone">' + json.businesses[i].display_phone + '</div>';
            html += '            <div class="col-md-12 description">' + json.businesses[i].snippet_text + '</div>';
            html += '          </div>';
            html += '        </div>';
            html += '      </div>';
            html += '    </div>';
      
          }
      
          $("#search_results").html(html);
        });

        $("#location").html("");

      }
    });

  }

  search();
  
});
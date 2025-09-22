// hide one-way and multi-city by default
$("#one-way-booking").hide();
$("#multi-city-booking").hide();


$("#trip-type-button").text($("#round-trip").text());


$("#trip-type-button").val($("#round-trip").text());


$("#trip-type li a").click(function () {
  console.log($(this).text());
  const selectedTrip = $(this).text();

  $("#trip-type-button").text($(this).text());

  $("#trip-type-button").val($(this).text());
    // Show )ne-way
  if (selectedTrip ===  $("#one-way").text()){
    $("#one-way-booking").show();
    $("#round-trip-booking").hide();
    $("#multi-city-booking").hide();

    // Show Round-Trip
  }  else if (selectedTrip ===  $("#round-trip").text()){
    $("#round-trip-booking").show();
    $("#one-way-booking").hide();
    $("#multi-city-booking").hide();

    // Show Multi-City
  } else if(selectedTrip ===  $("#multi-city").text()){
    $("#multi-city-booking").show();
    $("#round-trip-booking").hide();
    $("#one-way-booking").hide();
  }

//   $("add-trip-btn").click(function(e)
//   {
//     $(template(i++)).appendTo
//   })
});

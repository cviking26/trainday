//$.ajax({
//	url: "data.php",
//	context: document.body
//}).done(function() {
//		$( this ).addClass( "done" );
//});

/*Adding an new Content-Element, data-id is increasing by 1*/
function addElement(data){
	$("#content").append("<div class='content-el' data-id='" + ($(".content-el").last().data("id")+1) +"'><h1> " + data + " </h1></div>");
}

/*ready function*/
$(function(){
	getPlanOverview();
});

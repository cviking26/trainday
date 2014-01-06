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

function addNewElement(data){
	$("#content").append("<div class='content-el' data-id='" + ($(".content-el").last().data("id")+1) +"'><input class='content-input' type='text' name='newElement'></div>");
}

/*ready function*/
$(function(){
	getPlanOverview();

	/*live*/
	$("body").on("keypress", ".content-input", function(e) {
		if(e.keyCode==13){
			insertValue(this.value, this);
		}
	})
});

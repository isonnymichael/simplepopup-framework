// @ts-ignore
const plugin = SIMPLEPOPUP_PLUGIN;
const items = plugin.to_display;

for (let item of Object.values(items)){


	// Get the modal
	var modal = document.getElementById("myModal");

	// Get the <span> element that closes the modal
	var span = document.getElementsByClassName("close")[0];

	// When the user clicks on <span> (x), close the modal
	// @ts-ignore
	span.onclick = function() {
		modal.style.display = "none";
	}

	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
		if (event.target == modal) {
			modal.style.display = "none";
		}
	}

	// @ts-ignore
	const trigger = item.trigger;
	setTimeout(()=>{
		modal.style.display = "block";
	}, trigger)

}

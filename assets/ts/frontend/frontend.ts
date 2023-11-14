// @ts-ignore
const plugin = SIMPLEPOPUP_PLUGIN;
const items = plugin.to_display;

for (let item of Object.values(items)){
	// @ts-ignore
	const ID = item.ID;
	const modal = document.getElementById(`simplepopup-${ID}`);

	// Get the <span> element that closes the modal
	const span = document.getElementsByClassName(`simplepopup-close-${ID}`)[0];

	// When the user clicks on <span> (x), close the modal
	// @ts-ignore
	span.onclick = function() {
		modal.classList.toggle('fade');
	}

	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
		if (event.target == modal) {
			modal.classList.toggle('fade');
		}
	}

	// @ts-ignore
	const trigger = item.trigger;
	setTimeout(()=>{
		modal.classList.toggle('fade');
	}, trigger)

}

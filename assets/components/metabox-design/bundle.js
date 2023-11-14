const { useState } = React;
const App = () => {
	const simplePopupData = window.SIMPLEPOPUP_METABOX_DESIGN.data.simplepopup;
	const defaultOptions = window.SIMPLEPOPUP_METABOX_DESIGN.defaultOptions;

	// Grab Data
	const trigger = simplePopupData.trigger;
	const targeting = simplePopupData.targeting;
	const session = simplePopupData.session;
	const size = simplePopupData.size === '' ? 'medium' : simplePopupData.size;
	const display = simplePopupData.display;

    // State
	const [inputTrigger] = useState(trigger);
	const [selectedTargeting, setSelectedTargeting] = useState(targeting.target);
    const [targetingValue, setTargetingValue] = useState(targeting.value);
	const [selectedSession] = useState(session);
	const [selectedSize] = useState(size);
	const [selectedDisplay] = useState(display);

    const handleTargetingChange = (event) => {
        const newValue = event.target.value;
        setSelectedTargeting(newValue);
        setTargetingValue(newValue);

        // If 'page_id' or 'post_id' set input
        if (newValue === 'page_id' || newValue === 'post_id') {
            setTargetingValue(''); // Set to empty
        }
    };
    const handleInputChange = (event) => {
        setTargetingValue(event.target.value);
    };

	return (
		<div>
			<div className="simplepopup-mb">
				<label className="simplepopup-bold">
					Trigger
				</label>
				<div className="simplepopup-mt">
					<label className="input-group">
						<div className="input-group-area">
							<input type="number" min="0" name="simplepopup_settings[trigger]" defaultValue={inputTrigger} />
						</div>
						<div className="input-group-icon">ms</div>
					</label>
				</div>
			</div>

			<div className="simplepopup-form-group">
				<div className="simplepopup-mb simplepopup-mr">
					<label className="simplepopup-bold">
						Targeting
					</label>
					<div className="simplepopup-mt">
						<select
							value={selectedTargeting}
							name='simplepopup_settings[targeting][target]'
							onChange={handleTargetingChange}
						>
							{defaultOptions.targeting.type.map((targetingType) => (
								<option value={targetingType.id} key={targetingType.id}>{targetingType.text}</option>
							))}
						</select>
						<input
							name="simplepopup_settings[targeting][value]"
							type='text'
							placeholder='Enter ID'
							value={targetingValue}
							onChange={handleInputChange}
							style={{ display: selectedTargeting === 'page_id' || selectedTargeting === 'post_id' ? 'inline' : 'none' }}
						/>
						<p style={{ display: selectedTargeting === 'page_id' || selectedTargeting === 'post_id' ? 'block' : 'none' }}>
							Separate with comma if you want targeting more than 1 { selectedTargeting === 'page_id' ? 'Page':'Post' }, ex: 10,231
						</p>
					</div>
				</div>

				<div className="simplepopup-mb">
					<label className="simplepopup-bold">
						Session
					</label>
					<div className="simplepopup-mt">
						<select
							defaultValue={selectedSession}
							name='simplepopup_settings[session]'
						>
							{defaultOptions.session.type.map((sessionType) => (
								<option value={sessionType.id} key={sessionType.id}>{sessionType.text}</option>
							))}
						</select>
					</div>
				</div>
			</div>

			<div className="simplepopup-form-group">
				<div className="simplepopup-mb simplepopup-mr">
					<label className="simplepopup-bold">
						Display
					</label>
					<div className="simplepopup-mt">
						<select
							defaultValue={selectedDisplay}
							name='simplepopup_settings[display]'
						>
							{defaultOptions.display.type.map((displayType) => (
								<option value={displayType.id} key={displayType.id}>{displayType.text}</option>
							))}
						</select>
					</div>
				</div>

				<div>
					<label className="simplepopup-bold">
						Size
					</label>
					<div className="simplepopup-mt">
						<select
							defaultValue={selectedSize}
							name='simplepopup_settings[size_type]'
						>
							{defaultOptions.size.type.map((sizeType) => (
								<option value={sizeType.id} key={sizeType.id}>{sizeType.text}</option>
							))}
						</select>
					</div>
				</div>
			</div>
		</div>

	);
};
ReactDOM.render(<App />, document.getElementById('simplepopup-metabox-design-content'));

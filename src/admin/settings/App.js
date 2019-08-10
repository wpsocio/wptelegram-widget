/**
 * External dependencies
 */
import React from 'react';
// import { useState } from 'react';
import { Container, Row, Col } from 'react-bootstrap';
/**
 * Internal dependencies
 */
import Header from './components/Header';
import SettingsForm from './components/SettingsForm';
import Sidebar from './components/Sidebar';

const App = () => {
	// const [formState, setFormState] = useState({});
	return (
		<Container className="mw-100">
			<Row>
				<Col xs sm md={ 12 } lg={ 8 } xl={ 9 }>
					<Header />
					<SettingsForm /* setFormState={setFormState} */ />
				</Col>
				<Col xs sm md={ 12 } lg={ 4 } xl={ 3 }>
					{ /* <pre>{JSON.stringify(formState, null, 2)}</pre> */ }
					<Sidebar />
				</Col>
			</Row>
		</Container>
	);
};

export default App;

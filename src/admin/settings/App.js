import React, { useState } from 'react';
import Header from './components/Header';
import SettingsForm from './components/SettingsForm';
import Sidebar from './components/Sidebar';

const App = () => {
  // const [formState, setFormState] = useState({});

  return (
    <div className="wrapper">
      <div className="content col-lg-9 col-md-9">
        <Header />
        <SettingsForm /* setFormState={setFormState} *//>
      </div>
      <div className="sidebar col-lg-3 col-md-3">
        {/* <pre>{JSON.stringify(formState, null, 2)}</pre> */}
        <Sidebar />
      </div>
    </div>
  );
}

export default App;

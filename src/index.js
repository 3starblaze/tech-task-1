import React from "react";
import config from "./config";
import { createRoot } from 'react-dom/client';
import store from './store';
import { Provider } from 'react-redux';
import './index.scss';

createRoot(document.getElementById('root'))
  .render(
    <Provider store={store}>
      { config.routes[location.pathname]?.component || config.defaultComponent }
    </Provider>
  );

import React from "react";
import Root from "./RootComponent";
import { createRoot } from 'react-dom/client';
import store from './store';
import { Provider } from 'react-redux';

createRoot(document.getElementById('root'))
  .render(
    <Provider store={store}>
      <Root />
    </Provider>
  );

import React from "react";
import Root from "./RootComponent";
import AddProductPage from "./AddProductPage";
import { createRoot } from 'react-dom/client';
import store from './store';
import { Provider } from 'react-redux';
import './index.scss';

let component;

switch (location.pathname) {
case '/':
  component = <Root />;
  break;
case '/add-product':
  component = <AddProductPage />;
  break;
default:
  component = <div>Route not found!</div>;
}

createRoot(document.getElementById('root'))
  .render(
    <Provider store={store}>
      { component }
    </Provider>
  );

import React from "react";
import RootPage from "./RootPage";
import AddProductPage from "./AddProductPage";

const config = {
  baseUrl: 'http://localhost:8080',
  defaultComponent: <div>Route not found!</div>,
  routes: {
    '/': {
      name: 'root',
      component: <RootPage />,
    },
    '/add-product': {
      name: 'add-product',
      component: <AddProductPage />,
    },
  },
};

export default config;

export function route(name) {
  return Object.entries(config.routes)
    .find(([k, v]) => v.name === name)
    ?.[0];
}

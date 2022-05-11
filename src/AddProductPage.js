import React from 'react';
import styles from './AddProductPage.scss';

export default function Page() {
  return (
    <div>
      <header id="header">
        <h1>Product Add</h1>

        <div className="right-side">
          <button>Save</button>
          <button>Cancel</button>
        </div>
      </header>

      <div id="product_form">
        <label>SKU</label>
        <input id="sku"></input>

        <label>Name</label>
        <input id="name"></input>

        <label>Price ($)</label>
        <input id="price"></input>

        <label>Type switcher</label>
        <input id="productType"></input>

        { /* Dynamic form placeholder */ }
        <div style={{
               background: '#bbb',
               height: '10em',
               gridColumn: 'span 2',
             }} />
      </div>
    </div>
  );
}

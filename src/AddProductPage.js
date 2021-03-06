/**
 * @file The component for the add-product route.
 */

import React, { useEffect, useState } from 'react';
import Divider from './Divider';
import Footer from './Footer';
import config, { route } from './config';

/**
 * Get product data from `data` given productIdentifier `currentProduct`.
 *
 * @param data {object} The payload data
 * @param currentProduct {string} the product's `productIdentifier`
 * @return {(object|undefined)}
 */
function extractProductData(data, currentProduct) {
  return (data?.productData || [])
    .find(val => val.productIdentifier == currentProduct);
}

/**
 * Return an array of label-input element pairs which are deduced from `fields`.
 * @param {(object|undefined)} fields
 * @return {array}
 */
function renderFields(fields) {
  if (!fields) return;
  return fields.map((field, i) =>
    <React.Fragment key={ i }>
      <label htmlFor={ field.name }>{ field.label }</label>
      <input
        id={ field.styleId }
        name={ field.name }
        required
        { ...field.attributes }
      />
    </React.Fragment>
  )
}

export default function Page() {
  const [data, setData] = useState({}); // Form data fetched from API
  const [currentProduct, setCurrentProduct] = useState(null);

  useEffect(() => {
    fetch(config.baseUrl + '/api/products/form-data')
      .then(res => res.json())
      .then(json => {
        setData(json);
        // json is used directly because `setData` is asynchronous and `data`
        // variable is not available at the moment
        // TODO Handle this gracefully if `json[0]` cannot be accessed
        setCurrentProduct(json.productData[0].productIdentifier);
      });
  }, []);

  return (
    <div>
      <header id="header">
        <h1>Product Add</h1>

        <div className="right-side">
          <button
            onClick={ () =>
              document.getElementById('product_form').requestSubmit()
            }
          >Save</button>
          <button>
            <a href={ route('root') }>Cancel</a>
          </button>
        </div>
      </header>

      <Divider style={{ margin: '1em 0 2em 0' }} />

      <form
        id="product_form"
        action={ config.baseUrl + '/api/products/new' }
        method="post"
      >
        { renderFields(data?.baseFields) }
        <label htmlFor="productType">Type switcher</label>
        <select
          id="productType"
          name="productType"
          onChange={ ev => setCurrentProduct(ev.target.value) }
        >
          { (data?.productData || []).map(product =>
            <option
              key={ product.productIdentifier }
              value={ product.productIdentifier }>
              { product.formSelectValue }
            </option>
          ) }
        </select>
        { renderFields(extractProductData(data, currentProduct)?.fields) }
        <p style={{
             margin: 0,
             gridColumn: 'span 2',
           }}>
          { extractProductData(data, currentProduct)?.productDescription }
        </p>
      </form>

      <Divider style={{ margin: '2em 0 1em 0' }} />

      <Footer />
    </div>
  );
}

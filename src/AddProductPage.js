import React, { useEffect, useState } from 'react';
import styles from './AddProductPage.scss';

// FIXME remove redundant declaration
const baseUrl = 'http://localhost:8080';

function extractProductData(data, currentProduct) {
  return data.find(val => val.productIdentifier == currentProduct);
}

export default function Page() {
  const [data, setData] = useState([]); // Form data fetched from API
  const [currentProduct, setCurrentProduct] = useState(null);

  useEffect(() => {
    fetch(baseUrl + '/api/products/form-data')
      .then(res => res.json())
      .then(json => {
        setData(json);
        // json is used directly because `setData` is asynchronous and `data`
        // variable is not available at the moment
        // TODO Handle this gracefully if `json[0]` cannot be accessed
        setCurrentProduct(json[0].productIdentifier);
      });
  }, []);

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
        <select
          id="productType"
          onChange={ ev => setCurrentProduct(ev.target.value) }
        >
          { data.map(product =>
            <option
              key={ product.productIdentifier }
              value={ product.productIdentifier }>
              { product.productIdentifier }
            </option>
          ) }
        </select>
        {
          extractProductData(data, currentProduct)?.fields
            .map((field, i) =>
              <React.Fragment key={ i }>
                <label htmlFor={ field.name }>{ field.label }</label>
                <input
                  id={ field.styleId }
                  name={ field.name }
                />
              </React.Fragment>
            )
        }
        <p style={{
             margin: 0,
             gridColumn: 'span 2',
           }}>
          { extractProductData(data, currentProduct)?.productDescription }
        </p>
      </div>
    </div>
  );
}

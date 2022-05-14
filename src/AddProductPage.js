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
          <button
            onClick={ () =>
              document.getElementById('product_form').requestSubmit()
            }
          >Save</button>
          <button>Cancel</button>
        </div>
      </header>

      <form
        id="product_form"
        action={ baseUrl + '/api/products/new' }
        method="post"
      >
        <label htmlFor="sku">SKU</label>
        <input
          id="sku"
          name="sku"
          required
        />

        <label htmlFor="name">Name</label>
        <input
          id="name"
          name="name"
          required
        />

        <label htmlFor="price">Price ($)</label>
        <input
          id="price"
          name="price"
          type="number"
          step="0.01"
          required
        />

        <label htmlFor="productType">Type switcher</label>
        <select
          id="productType"
          name="productType"
          onChange={ ev => setCurrentProduct(ev.target.value) }
        >
          { data.map(product =>
            <option
              key={ product.productIdentifier }
              value={ product.productIdentifier }>
              { product.formSelectValue }
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
                  required
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
      </form>
    </div>
  );
}

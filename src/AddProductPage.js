import React, { useEffect, useState } from 'react';
import styles from './AddProductPage.scss';

// FIXME remove redundant declaration
const baseUrl = 'http://localhost:8080';

function extractProductData(data, currentProduct) {
  return (data?.productData || [])
    .find(val => val.productIdentifier == currentProduct);
}

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
    fetch(baseUrl + '/api/products/form-data')
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
          <button>Cancel</button>
        </div>
      </header>

      <form
        id="product_form"
        action={ baseUrl + '/api/products/new' }
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
    </div>
  );
}

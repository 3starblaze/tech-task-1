import React from "react";
import Card from "./Card.js";
import { useEffect } from 'react';
import { useSelector, useDispatch } from 'react-redux';
import { init } from './mainSlice';

const baseUrl = 'http://localhost:8080';

function deleteIds(ids) {
  return fetch(
    baseUrl + '/api/mass_delete', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ ids }),
    });
}

export default function Root() {
  const dispatch = useDispatch();
  const cards = useSelector(state => state.main.cards);
  const checkedCards = useSelector(
    state => Object.keys(cards).filter(id => cards[id].checked)
  );

  useEffect(() => {
    fetch(baseUrl + '/api/index')
      .then(res => res.json())
      .then(json => dispatch(init(json)));
  }, []);

  return (
    <>
      <button
        id="delete-product-btn"
        onClick={ () => {
          deleteIds(checkedCards)
            .then(_ => location = location) // refresh page
        } }>
        Mass delete
      </button>

      { /* FIXME Hardcoded URL */ }
      <button>
        <a href="add-product">Add</a>
      </button>

      <div className="card-container">
        {Object.keys(cards).map(id =>
          <Card key={id}
                databaseId={id}>
            { cards[id].indexCardData
              .map((line, i) => <p key={i}>{ line }</p>) }
          </Card>
        )}
      </div>
    </>
  );
}

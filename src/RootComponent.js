import React from "react";
import Card from "./Card.js";
import "./index.scss";
import { useEffect } from 'react';
import { useSelector, useDispatch } from 'react-redux';
import { init } from './mainSlice';

const baseUrl = 'http://localhost:8080';

export default function Root() {
  const dispatch = useDispatch();
  const cards = useSelector(state => state.main.cards);

  useEffect(() => {
    fetch(baseUrl + '/api/index')
      .then(res => res.json())
      .then(json => dispatch(init(json)));
  }, []);

  return (
    <div className="card-container">
      {cards.map(obj =>
        <Card key={obj.id}>
          { obj.indexCardData.map((line, i) => <p key={i}>{ line }</p>) }
        </Card>
      )}
    </div>
  );
}

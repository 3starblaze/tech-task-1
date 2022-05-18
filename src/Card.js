import React from "react";
import { useSelector, useDispatch } from 'react-redux';
import { toggleCheckbox } from './mainSlice';

export default function Card(props) {
  const dispatch = useDispatch();
  const cardInfo = useSelector(state => state.main.cards[props.databaseId]);

  return (
    <div className="card">
      <div className="heading">
        <input
          className="delete-checkbox"
          type="checkbox"
          checked={cardInfo.checked}
          onChange={ () => dispatch(toggleCheckbox(props.databaseId)) }/>
      </div>
      <div>
        {props.children}
      </div>
    </div>
  );
}

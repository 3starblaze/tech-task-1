import React from "react";
import { useSelector, useDispatch } from 'react-redux';
import { toggleCheckbox } from './mainSlice';

export default function Card(props) {
  const dispatch = useDispatch();
  const cardInfo = useSelector(state => state.main.cards[props.databaseId]);

  return (
    <div className="card">
      <input type="checkbox"
             checked={cardInfo.checked}
             onChange={ () => dispatch(toggleCheckbox(props.databaseId)) }/>
      {props.children}
    </div>
  );
}

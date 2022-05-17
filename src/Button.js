import React from "react";
import styles from "./Button.scss";

export default function Button(props) {
  return <button { ...props }>{ props.children }</button>;
}
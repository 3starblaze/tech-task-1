import React from "react";
import "./index.scss";

export default class Root extends React.Component {
  render() {
    return (
      <button
      id="press-me-btn"
      onClick={ () => alert('Thank you!')}>
        Press me :3
      </button>
    );
  }
}

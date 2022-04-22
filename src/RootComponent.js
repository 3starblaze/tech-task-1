import React from "react";

export default class Root extends React.Component {
  render() {
    return (
      <button onClick={ () => alert('Thank you!')}>
        Press me :3
      </button>
    );
  }
}

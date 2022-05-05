import React from "react";

export default class Card extends React.Component {
  render() {
    return (
      <div className="card" key={this.props.databaseId}>
        {this.props.children}
      </div>
    );
  }
}

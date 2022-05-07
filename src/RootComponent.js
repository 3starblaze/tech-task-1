import React from "react";
import Card from "./Card.js";
import "./index.scss";

const baseUrl = 'http://localhost:8080';

export default class Root extends React.Component {
  constructor() {
    super();
    // Index cards is expected to be an array of objects with these keys:
    // int `databaseId`
    // array[string] `indexCardData`
    this.state = {
      indexCards: [],
    };
  }

  componentDidMount() {
    fetch(baseUrl + '/api/index')
      .then(res => res.json())
      .then(json => this.setState({ indexCards: json }));
  }

  render() {
    console.log(this.state.indexCardData);
    return (
      <div className="card-container">
      {this.state.indexCards.map(obj =>
        (
          <Card databaseId={obj.databaseId}>
            { obj.indexCardData.map(line => <p>{ line }</p>) }
          </Card>
        )
      )}
      </div>
    );
  }
}

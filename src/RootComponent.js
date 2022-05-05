import React from "react";
import Card from "./Card.js";
import "./index.scss";

const baseUrl = 'http://localhost:8080';

export default class Root extends React.Component {
  constructor() {
    super();
    this.state = {
      indexCardData: [],
    };
  }

  componentDidMount() {
    fetch(baseUrl + '/api/index')
      .then(res => res.json())
      .then(json => this.setState({ indexCardData: json }));
  }

  render() {
    console.log(this.state.indexCardData);
    return (
      <div class="card-container">
      {this.state.indexCardData.map(obj =>
        (
          <Card databaseId={obj.databaseId}>
            <div dangerouslySetInnerHTML={{ __html: obj.cardTemplate}} />
          </Card>
        )
      )}
      </div>
    );
  }
}

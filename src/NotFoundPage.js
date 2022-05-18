import React from "react";
import { route } from './config';

export default function NotFoundPage() {
  return (
    <div>
      <h1 style={{ textAlign: 'center' }}>Not found!</h1>
      <p style={{ textAlign: 'center' }}>
        <a href={ route('root') }>Go home</a>
      </p>
    </div>
  )
}

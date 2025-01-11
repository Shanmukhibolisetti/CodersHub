import React from "react";
import "../Styles/Card.css";

function Card({ imgSrc, alt, title }) {
  return (
    <div className="column">
      <img src={imgSrc} alt={alt} width="100px" height="100px"/>
      <div className="caption" style={{ color: 'white', textDecoration: 'none' }}>{title}</div>
    </div>
  );
}

export default Card;


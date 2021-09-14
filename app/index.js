/*
import App from './App';

const { render } = wp.element;

render(
	<App />,
	document.getElementById( 'strava-watts-block' )
);
*/

import App from './App';

const { render, useState } = wp.element;

/*
const Votes = () => {
  const [votes, setVotes] = useState(0);
  const addVote = () => {
    setVotes(votes + 1);
  };
  return (
    <div>
      <h2>{votes} Votes</h2>
      <p>
        <button onClick={addVote}>Vote!</button>
      </p>
    </div>
  );
};
*/

render(<App />, document.getElementById('strava-watts-block'));
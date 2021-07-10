import React from "react";
import { BrowserRouter as Router, Switch, Route, Link } from "react-router-dom";
import MakeReservation from "./pages/MakeReservation";
import Dashboard from "./pages/Dashboard";

export default function App() {
  return (
    <Router>
      <div>
        <nav>
          <ul>
            <li>
              <Link to="/">Home</Link>
            </li>
            <li>
              <Link to="/dashboard">Dashboard</Link>
            </li>
          </ul>
        </nav>

        {/* A <Switch> looks through its children <Route>s and
            renders the first one that matches the current URL. */}
        <Switch>
          <Route path="/dashboard">
            <Dashboard />
          </Route>
          <Route path="/">
            <MakeReservation />
          </Route>
        </Switch>
      </div>
    </Router>
  );
}

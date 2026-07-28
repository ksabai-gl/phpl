import React from 'react';
import { BrowserRouter, Route, Switch, Link } from 'react-router-dom';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';

const queryClient = new QueryClient();

export function EnterpriseApp(): React.JSX.Element {
  return (
    <QueryClientProvider client={queryClient}>
      <BrowserRouter>
        <nav>
          <Link to="/enterprise">Enterprise modules</Link>
        </nav>
        <Switch>
          <Route exact path="/enterprise">
            <h1>Enterprise Module Hub</h1>
          </Route>
        </Switch>
      </BrowserRouter>
    </QueryClientProvider>
  );
}

export default EnterpriseApp;

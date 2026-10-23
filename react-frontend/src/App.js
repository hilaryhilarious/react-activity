// import logo from './logo.svg';
import './App.css';
import Button from './components/Button';
//import Dashboard from "./components/Dashboard";
import Card from './components/ui/Card';
import Form from './components/ui/Form';
import Counter from './components/ui/Counter';
import List from './components/ui/List';

//calling the card component
function App() {
  //return <Dashboard />;
  return (
    <>
    <h1>Hello React</h1>
      <Button/>
    <Card 
        name="Fate" 
        description="This is me."
    />
    <Card 
        name="Joy" 
        description="This is also me."
    />
    <Form />
    <Counter />
    <List />
    </>
  );
}
export default App;

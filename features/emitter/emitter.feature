Feature:
  So that I can have an event-based application
  As a developer
  I want an event emitter that dispatches events to listeners

  Scenario:
    Given that I have the following events I want to emit:
      | Name         |
      | event.first  |
      | event.second |
      | event.third  |
    When I emit the events
    Then a listener for each event receives them.

Feature: Techmeetups events

    Scenario: Successfully synchronize events
        Given a city is configured with some Meetup groups to fetch
        When the events are synchronized
        Then I should have some new events

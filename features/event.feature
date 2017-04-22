Feature: TechMeetups events

    Background:
        Given a city is configured with some Meetup groups to fetch
        When the events are synchronized

    @domain
    Scenario: Successfully synchronize events
        Then I should have some new events synchronized

    @ui
    Scenario: Successfully display events
        Then I should see some events on the homepage
        And a RSS feed is available

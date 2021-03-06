Feature: Fetch a fight
    In order to arbitrary decide of the superiority of a character
    As a consumer of the API
    I should be able to fetch a match

Scenario: Fetch a fight
    Given we have the following characters:
    | id | name         | picture |
    | 1  | Spider-man   | http://static.marvel.com/spider-man.jpg |
    | 2  | Iron-Man     | http://static.marvel.com/iron-man.jpg |
    When I send a GET request to "/fight"
    Then the response code should be 201

Scenario: A character cannot fight himself
    Given we have the following character:
    | id | name         | picture |
    | 1  | Spider-man   | http://static.marvel.com/spider-man.jpg |
    When I send a GET request to "/fight"
    Then the response code should be 204

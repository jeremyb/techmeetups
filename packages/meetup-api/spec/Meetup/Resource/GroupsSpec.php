<?php

namespace spec\Meetup\Resource;

use Http\Client\Common\HttpMethodsClient;
use Meetup\DTO\Group;
use Meetup\DTO\Query\FindGroupsQuery;
use Meetup\Hydrator\HydratorFactory;
use Meetup\Resource\Groups;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class GroupsSpec extends ObjectBehavior
{
    function let(HttpMethodsClient $httpClient)
    {
        $this->beConstructedWith($httpClient, HydratorFactory::create());
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Groups::class);
    }

    function it_should_find_groups(
        HttpMethodsClient $httpClient,
        ResponseInterface $response,
        StreamInterface $stream
    ) {
        $httpClient->get('/find/groups?category=34&location=Montpellier&radius=0')->shouldBeCalled()->willReturn($response);

        $response->getHeaderLine('Content-Type')->shouldBeCalled()->willReturn('application/json');
        $response->getBody()->shouldBeCalled()->willReturn($stream);
        $stream->__toString()->shouldBeCalled()->willReturn(file_get_contents(__DIR__ . '/../../fixtures/find_groups.json'));

        $results = $this->findGroups(FindGroupsQuery::from([
            'location' => 'Montpellier',
            'radius' => 0,
            'category' => 34,
        ]));
        $results->shouldBeArray();
        $results->shouldHaveCount(1);
        $group = $results[0];
        $group->shouldBeAnInstanceOf(Group::class);
        $group->id->shouldBe(18724486);
        $group->name->shouldBe('AFUP Montpellier');
        $group->joinMode->shouldBe('open');
        $group->urlname->shouldBe('Montpellier-PHP-Meetup');
    }
}

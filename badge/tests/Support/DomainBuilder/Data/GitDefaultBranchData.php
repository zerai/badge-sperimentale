<?php

declare(strict_types=1);

namespace Badge\Tests\Support\DomainBuilder\Data;

/**
 * null
 * @codeCoverageIgnore
 */
final class GitDefaultBranchData
{
    private const GITHUB_SOURCE = 'github.com';

    private const GITHUB_REPOSITORY_PREFIX = 'blob';

    private const BITBUCKET_SOURCE = 'bitbucket.org';

    private const BITBUCKET_REPOSITORY_PREFIX = 'src';

    private string $repository;

    private string $repositoryName;

    private string $defaultBranch;

    private int $httpStatusCode;

    private int $httpdelay;

    public function __construct(
        string $repository = '',
        string $repositoryName = '',
        string $defaultBranch = '',
        int $httpStatusCode = 200,
        int $httpdelay = 0
    ) {
        $this->repository = $repository;
        $this->repositoryName = $repositoryName;
        $this->defaultBranch = $defaultBranch;
        $this->httpStatusCode = $httpStatusCode;
        $this->httpdelay = $httpdelay;
    }

    public function repository(): string
    {
        return $this->repository;
    }

    public function withRepository(string $repository): self
    {
        return new self(
            $repository,
            $this->repositoryName,
            $this->defaultBranch,
            $this->httpStatusCode,
            $this->httpdelay
        );
    }

    public function repositoryName(): string
    {
        return $this->repositoryName;
    }

    public function withRepositoryName(string $repositoryName): self
    {
        return new self(
            $this->repository,
            $repositoryName,
            $this->defaultBranch,
            $this->httpStatusCode,
            $this->httpdelay
        );
    }

    public function defaultBranch(): string
    {
        return $this->defaultBranch;
    }

    public function withDefaultBranch(string $defaultBranch): self
    {
        return new self(
            $this->repository,
            $this->repositoryName,
            $defaultBranch,
            $this->httpStatusCode,
            $this->httpdelay
        );
    }

    public function httpStatusCode(): int
    {
        return $this->httpStatusCode;
    }

    public function withHttpStatusCode(int $httpStatusCode): self
    {
        return new self(
            $this->repository,
            $this->repositoryName,
            $this->defaultBranch,
            $httpStatusCode,
            $this->httpdelay
        );
    }

    public function httpdelay(): int
    {
        return $this->httpdelay;
    }

    public function withHttpdelay(int $httpdelay): self
    {
        return new self(
            $this->repository,
            $this->repositoryName,
            $this->defaultBranch,
            $this->httpStatusCode,
            $httpdelay
        );
    }

    public static function fromArray(array $data): self
    {
        if (! isset($data['repository']) || ! \is_string($data['repository'])) {
            throw new \InvalidArgumentException('Error on "repository": string expected');
        }

        if (! isset($data['repositoryName']) || ! \is_string($data['repositoryName'])) {
            throw new \InvalidArgumentException('Error on "repositoryName": string expected');
        }

        if (! isset($data['defaultBranch']) || ! \is_string($data['defaultBranch'])) {
            throw new \InvalidArgumentException('Error on "defaultBranch": string expected');
        }

        if (! isset($data['httpStatusCode']) || ! \is_int($data['httpStatusCode'])) {
            throw new \InvalidArgumentException('Error on "httpStatusCode": int expected');
        }

        if (! isset($data['httpdelay']) || ! \is_int($data['httpdelay'])) {
            throw new \InvalidArgumentException('Error on "httpdelay": int expected');
        }

        return new self(
            $data['repository'],
            $data['repositoryName'],
            $data['defaultBranch'],
            $data['httpStatusCode'],
            $data['httpdelay'],
        );
    }

    public function toArray(): array
    {
        return [
            'repository' => $this->repository,
            'repositoryName' => $this->repositoryName,
            'defaultBranch' => $this->defaultBranch,
            'httpStatusCode' => $this->httpStatusCode,
            'httpdelay' => $this->httpdelay,
        ];
    }

    public function equals(?self $other): bool
    {
        if (! $other instanceof self) {
            return false;
        }

        if ($this->repository !== $other->repository) {
            return false;
        }

        if ($this->repositoryName !== $other->repositoryName) {
            return false;
        }

        if ($this->defaultBranch !== $other->defaultBranch) {
            return false;
        }

        if ($this->httpStatusCode !== $other->httpStatusCode) {
            return false;
        }

        if ($this->httpdelay !== $other->httpdelay) {
            return false;
        }

        return true;
    }

    public function mockedEndPointForDefaultBranchRequest(): string
    {
        if ($this->isGitHub($this->repository)) {
            $endpoint = \sprintf('/repos/%s', $this->repositoryName);
        }

        if ($this->isBitbucket($this->repository)) {
            $endpoint = \sprintf('/2.0/repositories/%s', $this->repositoryName);
        }

        return $endpoint;
    }

    public function mockedEndPointForCommittedFileRequest(): string
    {
        if ($this->isGitHub($this->repository)) {
            $endpoint = \sprintf(
                '/%s/%s/%s/%s',
                $this->repositoryName(),
                $this->repositoryPrefix(),
                $this->defaultBranch,
                'composer.lock'
            );
        }
        if ($this->isBitbucket($this->repository)) {
            $endpoint = \sprintf(
                '/%s/%s/%s/%s',
                $this->repositoryName(),
                $this->repositoryPrefix(),
                $this->defaultBranch,
                'composer.lock'
            );
        }

        return $endpoint;
    }

    public function repositoryPrefix(): string
    {
        return $repositoryPrefix = $this->isGitHub() ? self::GITHUB_REPOSITORY_PREFIX : self::BITBUCKET_REPOSITORY_PREFIX;
    }

    public function mockedJson(): string
    {
        if ($this->isGitHub($this->repository)) {
            $dynamicData = $this->getGithubResponseDinamicData();
            $staticData = $this->getGithubResponseStaticData();
        }

        if ($this->isBitbucket($this->repository)) {
            $dynamicData = $this->getBitbucketResponseDinamicData();
            $staticData = $this->getBitbucketResponseStaticData();
        }

        $data = $dynamicData + $staticData;

        return \json_encode($data);
    }

    public function isGitHub(): bool
    {
        return \parse_url($this->repository, \PHP_URL_HOST) === self::GITHUB_SOURCE;
    }

    public function isBitbucket(): bool
    {
        return \parse_url($this->repository, \PHP_URL_HOST) === self::BITBUCKET_SOURCE;
    }

    public function getGithubResponseDinamicData(): array
    {
        return [
            'default_branch' => $this->defaultBranch,
        ];
    }

    public function getGithubResponseStaticData(): array
    {
        return [
            'id' => 7577612,
            'node_id' => 'MDEwOlJlcG9zaXRvcnk3NTc3NjEy',
            'name' => 'collections',
            'full_name' => 'doctrine/collections',
            'private' => false,
            'owner' => [
                'login' => 'doctrine',
                'id' => 209254,
                'node_id' => 'MDEyOk9yZ2FuaXphdGlvbjIwOTI1NA==',
                'avatar_url' => 'https://avatars.githubusercontent.com/u/209254?v=4',
                'gravatar_id' => '',
                'url' => 'https://api.github.com/users/doctrine',
                'html_url' => 'https://github.com/doctrine',
                'followers_url' => 'https://api.github.com/users/doctrine/followers',
                'following_url' => 'https://api.github.com/users/doctrine/following{/other_user}',
                'gists_url' => 'https://api.github.com/users/doctrine/gists{/gist_id}',
                'starred_url' => 'https://api.github.com/users/doctrine/starred{/owner}{/repo}',
                'subscriptions_url' => 'https://api.github.com/users/doctrine/subscriptions',
                'organizations_url' => 'https://api.github.com/users/doctrine/orgs',
                'repos_url' => 'https://api.github.com/users/doctrine/repos',
                'events_url' => 'https://api.github.com/users/doctrine/events{/privacy}',
                'received_events_url' => 'https://api.github.com/users/doctrine/received_events',
                'type' => 'Organization',
                'site_admin' => false,
            ],
            'html_url' => 'https://github.com/doctrine/collections',
            'description' => 'Collections Abstraction Library',
            'fork' => false,
            'url' => 'https://api.github.com/repos/doctrine/collections',
            'forks_url' => 'https://api.github.com/repos/doctrine/collections/forks',
            'keys_url' => 'https://api.github.com/repos/doctrine/collections/keys{/key_id}',
            'collaborators_url' => 'https://api.github.com/repos/doctrine/collections/collaborators{/collaborator}',
            'teams_url' => 'https://api.github.com/repos/doctrine/collections/teams',
            'hooks_url' => 'https://api.github.com/repos/doctrine/collections/hooks',
            'issue_events_url' => 'https://api.github.com/repos/doctrine/collections/issues/events{/number}',
            'events_url' => 'https://api.github.com/repos/doctrine/collections/events',
            'assignees_url' => 'https://api.github.com/repos/doctrine/collections/assignees{/user}',
            'branches_url' => 'https://api.github.com/repos/doctrine/collections/branches{/branch}',
            'tags_url' => 'https://api.github.com/repos/doctrine/collections/tags',
            'blobs_url' => 'https://api.github.com/repos/doctrine/collections/git/blobs{/sha}',
            'git_tags_url' => 'https://api.github.com/repos/doctrine/collections/git/tags{/sha}',
            'git_refs_url' => 'https://api.github.com/repos/doctrine/collections/git/refs{/sha}',
            'trees_url' => 'https://api.github.com/repos/doctrine/collections/git/trees{/sha}',
            'statuses_url' => 'https://api.github.com/repos/doctrine/collections/statuses/{sha}',
            'languages_url' => 'https://api.github.com/repos/doctrine/collections/languages',
            'stargazers_url' => 'https://api.github.com/repos/doctrine/collections/stargazers',
            'contributors_url' => 'https://api.github.com/repos/doctrine/collections/contributors',
            'subscribers_url' => 'https://api.github.com/repos/doctrine/collections/subscribers',
            'subscription_url' => 'https://api.github.com/repos/doctrine/collections/subscription',
            'commits_url' => 'https://api.github.com/repos/doctrine/collections/commits{/sha}',
            'git_commits_url' => 'https://api.github.com/repos/doctrine/collections/git/commits{/sha}',
            'comments_url' => 'https://api.github.com/repos/doctrine/collections/comments{/number}',
            'issue_comment_url' => 'https://api.github.com/repos/doctrine/collections/issues/comments{/number}',
            'contents_url' => 'https://api.github.com/repos/doctrine/collections/contents/{+path}',
            'compare_url' => 'https://api.github.com/repos/doctrine/collections/compare/{base}...{head}',
            'merges_url' => 'https://api.github.com/repos/doctrine/collections/merges',
            'archive_url' => 'https://api.github.com/repos/doctrine/collections/{archive_format}{/ref}',
            'downloads_url' => 'https://api.github.com/repos/doctrine/collections/downloads',
            'issues_url' => 'https://api.github.com/repos/doctrine/collections/issues{/number}',
            'pulls_url' => 'https://api.github.com/repos/doctrine/collections/pulls{/number}',
            'milestones_url' => 'https://api.github.com/repos/doctrine/collections/milestones{/number}',
            'notifications_url' => 'https://api.github.com/repos/doctrine/collections/notifications{?since,all,participating}',
            'labels_url' => 'https://api.github.com/repos/doctrine/collections/labels{/name}',
            'releases_url' => 'https://api.github.com/repos/doctrine/collections/releases{/id}',
            'deployments_url' => 'https://api.github.com/repos/doctrine/collections/deployments',
            'created_at' => '2013-01-12T16:37:17Z',
            'updated_at' => '2021-04-29T01:56:13Z',
            'pushed_at' => '2021-04-27T21:31:47Z',
            'git_url' => 'git://github.com/doctrine/collections.git',
            'ssh_url' => 'git@github.com:doctrine/collections.git',
            'clone_url' => 'https://github.com/doctrine/collections.git',
            'svn_url' => 'https://github.com/doctrine/collections',
            'homepage' => 'https://www.doctrine-project.org/projects/collections.html',
            'size' => 570,
            'stargazers_count' => 5152,
            'watchers_count' => 5152,
            'language' => 'PHP',
            'has_issues' => true,
            'has_projects' => true,
            'has_downloads' => true,
            'has_wiki' => false,
            'has_pages' => false,
            'forks_count' => 157,
            'mirror_url' => null,
            'archived' => false,
            'disabled' => false,
            'open_issues_count' => 37,
            'license' => [
                'key' => 'mit',
                'name' => 'MIT License',
                'spdx_id' => 'MIT',
                'url' => 'https://api.github.com/licenses/mit',
                'node_id' => 'MDc6TGljZW5zZTEz',
            ],
            'forks' => 157,
            'open_issues' => 37,
            'watchers' => 5152,
            'default_branch' => '1.6.x',
            'temp_clone_token' => null,
            'organization' => [
                'login' => 'doctrine',
                'id' => 209254,
                'node_id' => 'MDEyOk9yZ2FuaXphdGlvbjIwOTI1NA==',
                'avatar_url' => 'https://avatars.githubusercontent.com/u/209254?v=4',
                'gravatar_id' => '',
                'url' => 'https://api.github.com/users/doctrine',
                'html_url' => 'https://github.com/doctrine',
                'followers_url' => 'https://api.github.com/users/doctrine/followers',
                'following_url' => 'https://api.github.com/users/doctrine/following{/other_user}',
                'gists_url' => 'https://api.github.com/users/doctrine/gists{/gist_id}',
                'starred_url' => 'https://api.github.com/users/doctrine/starred{/owner}{/repo}',
                'subscriptions_url' => 'https://api.github.com/users/doctrine/subscriptions',
                'organizations_url' => 'https://api.github.com/users/doctrine/orgs',
                'repos_url' => 'https://api.github.com/users/doctrine/repos',
                'events_url' => 'https://api.github.com/users/doctrine/events{/privacy}',
                'received_events_url' => 'https://api.github.com/users/doctrine/received_events',
                'type' => 'Organization',
                'site_admin' => false,
            ],
            'network_count' => 157,
            'subscribers_count' => 27,
        ];
    }

    public function getBitbucketResponseDinamicData(): array
    {
        return [
            'mainbranch' => [
                'type' => 'branch',
                'name' => $this->defaultBranch,
            ],
        ];
    }

    public function getBitbucketResponseStaticData(): array
    {
        return [
            'scm' => 'git',
            'website' => '',
            'has_wiki' => false,
            'uuid' => '{1da0d0be-1dd2-4240-a261-588c5623c8c4}',
            'links' => [
                'watchers' => [
                    'href' => 'https://api.bitbucket.org/2.0/repositories/masnun/php-feeds/watchers',
                ],
                'branches' => [
                    'href' => 'https://api.bitbucket.org/2.0/repositories/masnun/php-feeds/refs/branches',
                ],
                'tags' => [
                    'href' => 'https://api.bitbucket.org/2.0/repositories/masnun/php-feeds/refs/tags',
                ],
                'commits' => [
                    'href' => 'https://api.bitbucket.org/2.0/repositories/masnun/php-feeds/commits',
                ],
                'clone' => [
                    [
                        'href' => 'https://bitbucket.org/masnun/php-feeds.git',
                        'name' => 'https',
                    ],
                    [
                        'href' => 'git@bitbucket.org:masnun/php-feeds.git',
                        'name' => 'ssh',
                    ],
                ],
                'self' => [
                    'href' => 'https://api.bitbucket.org/2.0/repositories/masnun/php-feeds',
                ],
                'source' => [
                    'href' => 'https://api.bitbucket.org/2.0/repositories/masnun/php-feeds/src',
                ],
                'html' => [
                    'href' => 'https://bitbucket.org/masnun/php-feeds',
                ],
                'avatar' => [
                    'href' => 'https://bytebucket.org/ravatar/%7B1da0d0be-1dd2-4240-a261-588c5623c8c4%7D?ts=php',
                ],
                'hooks' => [
                    'href' => 'https://api.bitbucket.org/2.0/repositories/masnun/php-feeds/hooks',
                ],
                'forks' => [
                    'href' => 'https://api.bitbucket.org/2.0/repositories/masnun/php-feeds/forks',
                ],
                'downloads' => [
                    'href' => 'https://api.bitbucket.org/2.0/repositories/masnun/php-feeds/downloads',
                ],
                'pullrequests' => [
                    'href' => 'https://api.bitbucket.org/2.0/repositories/masnun/php-feeds/pullrequests',
                ],
            ],
            'fork_policy' => 'allow_forks',
            'full_name' => 'masnun/php-feeds',
            'name' => 'php-feeds',
            'project' => [
                'links' => [
                    'self' => [
                        'href' => 'https://api.bitbucket.org/2.0/workspaces/masnun/projects/PROJ',
                    ],
                    'html' => [
                        'href' => 'https://bitbucket.org/masnun/workspace/projects/PROJ',
                    ],
                    'avatar' => [
                        'href' => 'https://bitbucket.org/account/user/masnun/projects/PROJ/avatar/32?ts=1543463252',
                    ],
                ],
                'type' => 'project',
                'name' => 'Untitled project',
                'key' => 'PROJ',
                'uuid' => '{2924bbcb-2a41-4b09-adb7-dc7071c0da24}',
            ],
            'language' => 'php',
            'created_on' => '2012-02-03T09:33:43.058828+00:00',
            'mainbranch' => [
                'type' => 'branch',
                'name' => 'master',
            ],
            'workspace' => [
                'slug' => 'masnun',
                'type' => 'workspace',
                'name' => 'Abu Ashraf Masnun',
                'links' => [
                    'self' => [
                        'href' => 'https://api.bitbucket.org/2.0/workspaces/masnun',
                    ],
                    'html' => [
                        'href' => 'https://bitbucket.org/masnun/',
                    ],
                    'avatar' => [
                        'href' => 'https://bitbucket.org/workspaces/masnun/avatar/?ts=1543463252',
                    ],
                ],
                'uuid' => '{01ef4403-8ac7-402a-a1b5-10f6b6f2903c}',
            ],
            'has_issues' => false,
            'owner' => [
                'display_name' => 'Abu Ashraf Masnun',
                'uuid' => '{01ef4403-8ac7-402a-a1b5-10f6b6f2903c}',
                'links' => [
                    'self' => [
                        'href' => 'https://api.bitbucket.org/2.0/users/%7B01ef4403-8ac7-402a-a1b5-10f6b6f2903c%7D',
                    ],
                    'html' => [
                        'href' => 'https://bitbucket.org/%7B01ef4403-8ac7-402a-a1b5-10f6b6f2903c%7D/',
                    ],
                    'avatar' => [
                        'href' => 'https://secure.gravatar.com/avatar/2c87e8db57897b08332340b2c4b508ff?d=https%3A%2F%2Favatar-management--avatars.us-west-2.prod.public.atl-paas.net%2Finitials%2FAM-5.png',
                    ],
                ],
                'type' => 'user',
                'nickname' => 'masnun',
                'account_id' => '557058:b814c028-c0fc-4330-ae3b-7a3441b33222',
            ],
            'updated_on' => '2012-02-03T09:36:11.860116+00:00',
            'size' => 8001,
            'type' => 'repository',
            'slug' => 'php-feeds',
            'is_private' => false,
            'description' => 'php-feeds is a feed aggregation program written in php and the zend framework ',
        ];
    }
}

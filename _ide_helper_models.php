<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Game
 *
 * @property int $id
 * @property string $name
 * @property int|null $year
 * @property int|null $players_min
 * @property int|null $players_max
 * @property int|null $base_game_id
 * @property string|null $full_name
 * @property int|null $approved_by_admin
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read Game|null $baseGame
 * @property-read \Illuminate\Database\Eloquent\Collection|Game[] $expansions
 * @property-read int|null $expansions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\GroupGame[] $groupGames
 * @property-read int|null $group_games_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PlayedGame[] $playedGameExpansions
 * @property-read int|null $played_game_expansions_count
 * @method static \Database\Factories\GameFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Game newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Game newQuery()
 * @method static \Illuminate\Database\Query\Builder|Game onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Game query()
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereApprovedByAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereBaseGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game wherePlayersMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game wherePlayersMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereYear($value)
 * @method static \Illuminate\Database\Query\Builder|Game withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Game withoutTrashed()
 */
	class Game extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Group
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int|null $admin_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $admin
 * @property-read mixed $display
 * @property-read mixed $type_member
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\GroupGame[] $groupGames
 * @property-read int|null $group_games_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\GroupUser[] $groupUsers
 * @property-read int|null $group_users_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PlayedGame[] $playedGames
 * @property-read int|null $played_games_count
 * @method static \Database\Factories\GroupFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Group newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Group newQuery()
 * @method static \Illuminate\Database\Query\Builder|Group onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Group query()
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Group withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Group withoutTrashed()
 */
	class Group extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\GroupGame
 *
 * @property int $id
 * @property int $group_id
 * @property int $game_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Game $game
 * @property-read \App\Models\Group $group
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\GroupGameLink[] $links
 * @property-read int|null $links_count
 * @method static \Database\Factories\GroupGameFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupGame newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupGame newQuery()
 * @method static \Illuminate\Database\Query\Builder|GroupGame onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupGame query()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupGame whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupGame whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupGame whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupGame whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupGame whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupGame whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|GroupGame withTrashed()
 * @method static \Illuminate\Database\Query\Builder|GroupGame withoutTrashed()
 */
	class GroupGame extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\GroupGameLink
 *
 * @property int $id
 * @property int $group_game_id
 * @property string $name
 * @property string $link
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\GroupGame $groupGame
 * @method static \Database\Factories\GroupGameLinkFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupGameLink newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupGameLink newQuery()
 * @method static \Illuminate\Database\Query\Builder|GroupGameLink onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupGameLink query()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupGameLink whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupGameLink whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupGameLink whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupGameLink whereGroupGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupGameLink whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupGameLink whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupGameLink whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupGameLink whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|GroupGameLink withTrashed()
 * @method static \Illuminate\Database\Query\Builder|GroupGameLink withoutTrashed()
 */
	class GroupGameLink extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\GroupUser
 *
 * @property int $id
 * @property string $firstname
 * @property string $name
 * @property int $group_id
 * @property int|null $user_id
 * @property string|null $code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PlayedGame[] $gameCreator
 * @property-read int|null $game_creator_count
 * @property-read mixed $full_name
 * @property-read \App\Models\Group $group
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\GroupUserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUser newQuery()
 * @method static \Illuminate\Database\Query\Builder|GroupUser onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUser whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUser whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUser whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUser whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUser whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUser whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|GroupUser withTrashed()
 * @method static \Illuminate\Database\Query\Builder|GroupUser withoutTrashed()
 */
	class GroupUser extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PlayedGame
 *
 * @property int $id
 * @property int $group_id
 * @property int $game_id
 * @property int|null $winner_id
 * @property int|null $creator_id
 * @property string|null $date
 * @property string|null $time_played
 * @property string|null $remarks
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $creator
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Game[] $expansions
 * @property-read int|null $expansions_count
 * @property-read \App\Models\Game $game
 * @property-read \App\Models\Group $group
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PlayedGameScore[] $scores
 * @property-read int|null $scores_count
 * @property-read \App\Models\GroupUser|null $winner
 * @method static \Database\Factories\PlayedGameFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayedGame newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlayedGame newQuery()
 * @method static \Illuminate\Database\Query\Builder|PlayedGame onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PlayedGame query()
 * @method static \Illuminate\Database\Eloquent\Builder|PlayedGame whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayedGame whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayedGame whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayedGame whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayedGame whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayedGame whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayedGame whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayedGame whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayedGame whereTimePlayed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayedGame whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayedGame whereWinnerId($value)
 * @method static \Illuminate\Database\Query\Builder|PlayedGame withTrashed()
 * @method static \Illuminate\Database\Query\Builder|PlayedGame withoutTrashed()
 */
	class PlayedGame extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PlayedGameScore
 *
 * @property int $id
 * @property int $played_game_id
 * @property int $group_user_id
 * @property int|null $score
 * @property int|null $place
 * @property string|null $remarks
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\GroupUser $groupUser
 * @property-read \App\Models\PlayedGame $playedGame
 * @method static \Database\Factories\PlayedGameScoreFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayedGameScore newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlayedGameScore newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlayedGameScore query()
 * @method static \Illuminate\Database\Eloquent\Builder|PlayedGameScore whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayedGameScore whereGroupUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayedGameScore whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayedGameScore wherePlace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayedGameScore wherePlayedGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayedGameScore whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayedGameScore whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayedGameScore whereUpdatedAt($value)
 */
	class PlayedGameScore extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $firstname
 * @property string $name
 * @property string $email
 * @property string|null $username
 * @property string|null $role
 * @property int|null $favorite_group_id
 * @property string|null $last_login
 * @property string|null $premium
 * @property int|null $forget_user
 * @property string $password
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Group|null $group
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Query\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFavoriteGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereForgetUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePremium($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @method static \Illuminate\Database\Query\Builder|User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|User withoutTrashed()
 */
	class User extends \Eloquent {}
}


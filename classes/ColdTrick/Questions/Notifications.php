<?php

namespace ColdTrick\Questions;

use Elgg\Notifications\SubscriptionNotificationEvent;

class Notifications {
	
	/**
	 * Set the correct message content for when a question is created
	 *
	 * @param string                           $hook         the name of the hook
	 * @param string                           $type         the type of the hook
	 * @param \Elgg\Notifications\Notification $return_value current return value
	 * @param array                            $params       supplied params
	 *
	 * @return void|\Elgg\Notifications\Notification
	 */
	public static function createQuestion($hook, $type, $return_value, $params) {
		
		if (!$return_value instanceof \Elgg\Notifications\Notification) {
			return;
		}
		
		$event = elgg_extract('event', $params);
		$recipient = elgg_extract('recipient', $params);
		$language = elgg_extract('language', $params);
		
		if (!$event instanceof SubscriptionNotificationEvent || !$recipient instanceof \ElggUser) {
			return;
		}
		
		$actor = $event->getActor();
		$question = $event->getObject();
		
		$return_value->subject = elgg_echo('questions:notifications:create:subject', [], $language);
		$return_value->summary = elgg_echo('questions:notifications:create:summary', [], $language);
		$return_value->body = elgg_echo('questions:notifications:create:message', [
			$recipient->getDisplayName(),
			$question->getDisplayName(),
			$question->getURL(),
		], $language);
		
		return $return_value;
	}
	
	/**
	 * Set the correct message content for when a question is moved
	 *
	 * @param string                           $hook         the name of the hook
	 * @param string                           $type         the type of the hook
	 * @param \Elgg\Notifications\Notification $return_value current return value
	 * @param array                            $params       supplied params
	 *
	 * @return void|\Elgg\Notifications\Notification
	 */
	public static function moveQuestion($hook, $type, $return_value, $params) {
		
		if (!$return_value instanceof \Elgg\Notifications\Notification) {
			return;
		}
		
		$event = elgg_extract('event', $params);
		$recipient = elgg_extract('recipient', $params);
		$language = elgg_extract('language', $params);
		
		if (!$event instanceof SubscriptionNotificationEvent || !$recipient instanceof \ElggUser) {
			return;
		}
		
		$actor = $event->getActor();
		$question = $event->getObject();
		
		$return_value->subject = elgg_echo('questions:notifications:move:subject', [], $language);
		$return_value->summary = elgg_echo('questions:notifications:move:summary', [], $language);
		$return_value->body = elgg_echo('questions:notifications:move:message', [
			$recipient->getDisplayName(),
			$question->getDisplayName(),
			$question->getURL(),
		], $language);
		
		return $return_value;
	}
	
	/**
	 * Set the correct message content for when a answer is created
	 *
	 * @param string                           $hook         the name of the hook
	 * @param string                           $type         the type of the hook
	 * @param \Elgg\Notifications\Notification $return_value current return value
	 * @param array                            $params       supplied params
	 *
	 * @return void|\Elgg\Notifications\Notification
	 */
	public static function createAnswer($hook, $type, $return_value, $params) {
		
		if (!$return_value instanceof \Elgg\Notifications\Notification) {
			return;
		}
		
		$event = elgg_extract('event', $params);
		$recipient = elgg_extract('recipient', $params);
		$language = elgg_extract('language', $params);
		
		if (!$event instanceof SubscriptionNotificationEvent || !$recipient instanceof \ElggUser) {
			return;
		}
		
		$actor = $event->getActor();
		$answer = $event->getObject();
		$question = $answer->getContainerEntity();
		
		$return_value->subject = elgg_echo('questions:notifications:answer:create:subject', [$question->getDisplayName()], $language);
		$return_value->summary = elgg_echo('questions:notifications:answer:create:summary', [$question->getDisplayName()], $language);
		$return_value->body = elgg_echo('questions:notifications:answer:create:message', [
			$recipient->getDisplayName(),
			$actor->getDisplayName(),
			$question->getDisplayName(),
			$answer->description,
			$answer->getURL(),
		], $language);
		
		return $return_value;
	}
	
	/**
	 * Set the correct message content for when a answer is marked as correct
	 *
	 * @param string                           $hook         the name of the hook
	 * @param string                           $type         the type of the hook
	 * @param \Elgg\Notifications\Notification $return_value current return value
	 * @param array                            $params       supplied params
	 *
	 * @return void|\Elgg\Notifications\Notification
	 */
	public static function correctAnswer($hook, $type, $return_value, $params) {
		
		if (!$return_value instanceof \Elgg\Notifications\Notification) {
			return;
		}
		
		$event = elgg_extract('event', $params);
		$recipient = elgg_extract('recipient', $params);
		$language = elgg_extract('language', $params);
		
		if (!$event instanceof SubscriptionNotificationEvent || !$recipient instanceof \ElggUser) {
			return;
		}
		
		$actor = $event->getActor();
		$answer = $event->getObject();
		$question = $answer->getContainerEntity();
		
		$return_value->subject = elgg_echo('questions:notifications:answer:correct:subject', [$question->getDisplayName()], $language);
		$return_value->summary = elgg_echo('questions:notifications:answer:correct:summary', [$question->getDisplayName()], $language);
		$return_value->body = elgg_echo('questions:notifications:answer:correct:message', [
			$recipient->getDisplayName(),
			$actor->getDisplayName(),
			$question->getDisplayName(),
			$answer->description,
			$answer->getURL(),
		], $language);
		
		return $return_value;
	}
	
	/**
	 * Change the notification message for comments on answers
	 *
	 * @param string                           $hook         the name of the hook
	 * @param string                           $type         the type of the hook
	 * @param \Elgg\Notifications\Notification $return_value the current return value
	 * @param array                            $params       supplied values
	 *
	 * @return void|\Elgg\Notifications\Notification
	 */
	public static function createCommentOnAnswer($hook, $type, $return_value, $params) {
		
		if (!$return_value instanceof \Elgg\Notifications\Notification) {
			return;
		}
		
		$event = elgg_extract('event', $params);
		if (!$event instanceof SubscriptionNotificationEvent) {
			return;
		}
		
		if ($event->getAction() !== 'create') {
			return;
		}
		
		$comment = $event->getObject();
		if (!$comment instanceof \ElggComment) {
			return;
		}
		
		$object = $comment->getContainerEntity();
		if (!$object instanceof \ElggAnswer) {
			return;
		}
		
		$actor = $event->getActor();
		$question = $object->getContainerEntity();
		$language = elgg_extract('language', $params, get_current_language());
		$recipient = elgg_extract('recipient', $params);
	
		$return_value->subject = elgg_echo('questions:notifications:answer:comment:subject', [], $language);
		$return_value->summary = elgg_echo('questions:notifications:answer:comment:summary', [], $language);
		$return_value->body = elgg_echo('questions:notifications:answer:comment:message', [
			$recipient->getDisplayName(),
			$actor->getDisplayName(),
			$question->getDisplayName(),
			$comment->description,
			$object->getURL(),
		], $language);
		
		return $return_value;
	}
	
	/**
	 * Add experts to the subscribers for a question
	 *
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param array  $return_value current return value
	 * @param array  $params       supplied params
	 *
	 * @return void|array
	 */
	public static function addExpertsToSubscribers($hook, $type, $return_value, $params) {
		
		$event = elgg_extract('event', $params);
		if (!$event instanceof SubscriptionNotificationEvent) {
			return;
		}
		
		if (!in_array($event->getAction(), ['create', 'move'])) {
			return;
		}
		
		$question = $event->getObject();
		if (!$question instanceof \ElggQuestion) {
			return;
		}
		
		$moving = ($event->getAction() === 'moving');
		// when moving only notify experts
		if ($moving) {
			$return_value = [];
		}
		
		if (!questions_experts_enabled()) {
			// experts isn't enabled
			return $return_value;
		}
		
		$container = $question->getContainerEntity();
		if (!$container instanceof \ElggGroup) {
			$container = elgg_get_site_entity();
		}
		
		$experts = [];
		
		$users = elgg_get_entities([
			'type' => 'user',
			'site_guids' => false,
			'limit' => false,
			'relationship' => QUESTIONS_EXPERT_ROLE,
			'relationship_guid' => $container->guid,
			'inverse_relationship' => true,
		]);
		if (!empty($users)) {
			$experts = $users;
		}
		
		// trigger a hook so others can extend the list
		$params = [
			'entity' => $question,
			'experts' => $experts,
			'moving' => $moving,
		];
		$experts = elgg_trigger_plugin_hook('notify_experts', 'questions', $params, $experts);
		if (empty($experts) || !is_array($experts)) {
			return;
		}
		
		foreach ($experts as $expert) {
			if (!isset($return_value[$expert->guid])) {
				// no notification for this user, so add email notification
				$return_value[$expert->guid] = ['email'];
				continue;
			}
			
			if (!in_array('email', $return_value[$expert->guid])) {
				// user already got a notification, but not email so add that
				$return_value[$expert->guid][] = 'email';
				continue;
			}
		}
		
		// small bit of cleanup
		unset($experts);
		
		return $return_value;
	}
	
	/**
	 * Add question owner to the subscribers for an answer
	 *
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param array  $return_value current return value
	 * @param array  $params       supplied params
	 *
	 * @return void|array
	 */
	public static function addQuestionOwnerToAnswerSubscribers($hook, $type, $return_value, $params) {
		
		$event = elgg_extract('event', $params);
		if (!$event instanceof SubscriptionNotificationEvent) {
			return;
		}
		
		if (!in_array($event->getAction(), ['create', 'correct'])) {
			return;
		}
		
		$object = $event->getObject();
		if (!$object instanceof \ElggObject) {
			return;
		}
		
		$question = false;
		$container = $object->getContainerEntity();
		if ($object instanceof \ElggAnswer) {
			$question = $container;
		} elseif ($container instanceof \ElggAnswer) {
			// comments on answers
			$question = $container->getContainerEntity();
		}
		
		if (!$question instanceof \ElggQuestion) {
			// something went wrong, maybe access
			return;
		}
		
		/* @var $owner \ElggUser */
		$owner = $question->getOwnerEntity();
		
		$filtered_methods = self::getEnabledNotificationSettings($owner);
		if (empty($filtered_methods)) {
			return;
		}
		
		$return_value[$owner->guid] = $filtered_methods;
		
		return $return_value;
	}
	
	/**
	 * Add answer owner to the subscribers for an answer
	 *
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param array  $return_value current return value
	 * @param array  $params       supplied params
	 *
	 * @return void|array
	 */
	public static function addAnswerOwnerToAnswerSubscribers($hook, $type, $return_value, $params) {
		
		$event = elgg_extract('event', $params);
		if (!$event instanceof SubscriptionNotificationEvent) {
			return;
		}
		
		if (!in_array($event->getAction(), ['create', 'correct'])) {
			return;
		}
		
		$answer = $event->getObject();
		if (!$answer instanceof \ElggAnswer) {
			return;
		}
		
		/* @var $owner \ElggUser */
		$owner = $answer->getOwnerEntity();
		
		$filtered_methods = self::getEnabledNotificationSettings($owner);
		if (empty($filtered_methods)) {
			return;
		}
		
		$return_value[$owner->guid] = $filtered_methods;
		
		return $return_value;
	}
	
	/**
	 * Add question subscribers to the subscribers for an answer
	 *
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param array  $return_value current return value
	 * @param array  $params       supplied params
	 *
	 * @return void|array
	 */
	public static function addQuestionSubscribersToAnswerSubscribers($hook, $type, $return_value, $params) {
		
		$event = elgg_extract('event', $params);
		if (!$event instanceof SubscriptionNotificationEvent) {
			return;
		}
		
		if (!in_array($event->getAction(), ['create', 'correct'])) {
			return;
		}
		
		$object = $event->getObject();
		if (!$object instanceof \ElggEntity) {
			return;
		}
		$container = $object->getContainerEntity();
		if (!$container instanceof \ElggAnswer) {
			return;
		}
		
		$question = $container->getContainerEntity();
		$subscribers = elgg_get_subscriptions_for_container($question->guid);
		if (empty($subscribers)) {
			return;
		}
		
		return ($return_value + $subscribers);
	}
	
	/**
	 * Return the active notifiaction methods of a user
	 *
	 * @param \ElggUser $user the user to check
	 *
	 * @return string[]
	 */
	protected static function getEnabledNotificationSettings(\ElggUser $user) {
		
		if (!$user instanceof \ElggUser) {
			return [];
		}
		
		$methods = $user->getNotificationSettings();
		if (empty($methods)) {
			return [];
		}
		
		$filtered_methods = [];
		foreach ($methods as $method => $value) {
			if (empty($value)) {
				continue;
			}
			$filtered_methods[] = $method;
		}
		
		return $filtered_methods;
	}
}

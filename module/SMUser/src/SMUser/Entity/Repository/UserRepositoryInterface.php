<?php
/**
 * @file UserRepositoryInterface.php
 * @date Sep 30, 2013 
 * @author Sandro Meier
 */
 
namespace SMUser\Entity\Repository;

interface UserRepositoryInterface
{
	/**
	 * Find a user by username. 
	 * 
	 * @return SMUser\Entity\UserInterface|NULL
	 */
	public function findOneByUsername($username);
	
	/**
	 * Find a user by id
	 * 
	 * @return SMUser\Entity\UserInterface|NULL
	 */
	public function findOneById($id);
	
	/**
	 * Find all user. 
	 * @return array An array containing all users.
	 */
	public function findAll();
	
	/**
	 * Should create and return a new entity
	 */
	public function createNewUser();
	
	/**
	 * Should persist the user. 
	 * 
	 * @param SMUser\Entity\UserInterface $user
	 */
	public function saveUser($user);
	
	/**
	 * Should delete (and persist) the user
	 */
	public function removeUser($user);
}
<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Repositories\UserRepository;
use App\Services\UserService;
use PHPUnit\Framework\TestCase;
use Tests\Unit\BaseTest;
use App\Exceptions\BusinessException;
use App\Exceptions\SystemException;
use App\Security\JWTUtils;
use App\Services\ServiceUtils;
use Exception;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotEmpty;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

class UserServiceTest extends BaseTest
{

    private $userRepositoryMock;
    private $userService;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var UserRepository&\PHPUnit\Framework\MockObject\MockObject */
        $this->userRepositoryMock = $this->createMock(UserRepository::class);

        $this->userService = new UserService($this->userRepositoryMock);
    }

    // public function testShouldLoginUser()
    // {
    //     $username = 'testuser@ymail.com';
    //     $password = '123456';

    //     $userObject = [
    //         'id' => 1,
    //         'user_name' => $username,
    //         'monthly_income' => "1",
    //         'role' => 'user'
    //     ];

    //     $this->userRepositoryMock->expects($this->once())->method('getByUsernameAndPassword')->with($username, $password)->willReturn($userObject);
    //     $jwtUtilsMock = $this->getMockBuilder("App\Security\JWTUtils")->getMock();
    //     $jwtUtilsMock->method('generateJwtToken')->with($userObject);

    //     \Mockery::mock('alias:App\Services\ServiceUtils')
    //         ->shouldReceive('setCookieValue')
    //         ->once();

    //     $result = $this->userService->loginUser($username, $password);

    //     assertNotEmpty($result);
    //     assertTrue($result);
    // }

    public function testShouldCreateUser()
    {
        $user = [
            'id' => 1,
            'user_name' => "test@ymail.com",
            'monthly_income' => '10000'
        ];

        $this->userRepositoryMock->expects($this->once())->method('create')->with($user);

        $this->userService->createUser($user);

        assertTrue(true);
    }

    public function testShouldCreateUserAsAdmin()
    {
        $user = [
            'id' => 1,
            'user_name' => "admin@ymail.com",
            'monthly_income' => '10000',
            'role' => 'admin'
        ];

        $this->userRepositoryMock->expects($this->once())->method('create')->with($user);

        $this->userService->createUser($user);

        assertTrue(true);
    }

    public function testShouldUpdateMonthlyIncome()
    {
        $user = [
            'id' => 1,
            'user_name' => "test@ymail.com",
            'monthly_income' => '10000',
            'role' => 'admin',
            'password' => '12345'
        ];

        $this->userRepositoryMock->expects($this->once())->method('getByUsernameAndPassword')->with($user['user_name'], $user['password'])->willReturn($user);
        $this->userRepositoryMock->expects($this->once())->method('updateMonthlyIncome')->with($user['id'], $user['monthly_income']);

        $this->userService->updateMonthlyIncome($user);

        assertTrue(true);
    }

    public function testShouldCreateUserThrowException()
    {
        $user = [
            'id' => 1,
            'user_name' => "admin@ymail.com",
            'monthly_income' => '10000',
            'role' => 'admin',
            'password' => '12345'
        ];

        $this->userRepositoryMock->method('create')->willThrowException(new \Exception('Database failure'));

        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('Database failure');

        $this->userService->createUser($user);
    }

    public function testShouldThrowBusinessExceptionForUpdateMonthlyIncome()
    {
        $user = [
            'id' => 1,
            'user_name' => "test@ymail.com",
            'monthly_income' => '10000',
            'role' => 'admin',
            'password' => '12345'
        ];

        $this->userRepositoryMock->method('getByUsernameAndPassword')
            ->with($user['user_name'], $user['password'])
            ->willReturn(null);
        $this->userRepositoryMock->method('getByUsernameAndPassword')->willThrowException(new BusinessException(400, 'User Not Found'));

        $this->expectException(BusinessException::class);
        $this->expectExceptionCode(400);
        $this->expectExceptionMessage('User Not Found');

        $this->userService->updateMonthlyIncome($user);
    }

    public function testShouldUpdateMonthlyIncomeWithException()
    {
        $user = [
            'id' => 1,
            'user_name' => "test@ymail.com",
            'monthly_income' => '10000',
            'role' => 'admin',
            'password' => '12345'
        ];

        $this->userRepositoryMock->expects($this->once())->method('getByUsernameAndPassword')->with($user['user_name'], $user['password'])->willReturn($user);
        $this->userRepositoryMock->method('updateMonthlyIncome')->willThrowException(new \Exception('Database failure'));

        $this->expectException(SystemException::class);
        $this->expectExceptionMessage('Database failure');

        $this->userService->updateMonthlyIncome($user);
    }

    public function testShouldUpdatePassword()
    {
        $username = 'test@ymail.com';
        $password = '123456';

        $this->userRepositoryMock->expects($this->once())->method('updatePassword')->with($username, $password);

        $this->userService->updatePassword($username, $password);
    }

    public function testShouldThrowExceptionForUpdatePassword()
    {
        $username = 'test@ymail.com';
        $password = '123456';

        $this->userRepositoryMock->expects($this->once())->method('updatePassword')->willThrowException(new \Exception('Database Access Exception'));

        $this->expectException(SystemException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage('Database Access Exception');

        $this->userService->updatePassword($username, $password);
    }

    public function testShouldGetAllUser()
    {
        $users = [];
        $userObject = [];

        $users[] = $this->getUser(1, "test@ymail.com");
        $users[] = $this->getUser(2, "test2@ymail.com");

        $userObject[] = $this->getUserObject(1, "test@ymail.com");
        $userObject[] = $this->getUserObject(2, "test2@ymail.com");

        $this->userRepositoryMock->expects($this->once())->method('getAll')->willReturn($userObject);

        $result = $this->userService->getAllUser();

        assertNotNull($result);
        assertEquals(2, sizeof($result));
    }

    public function testShouldThrowExceptionForGetAllUser()
    {
        $users = [];
        $userObject = [];

        $users[] = $this->getUser(1, "test@ymail.com");
        $users[] = $this->getUser(2, "test2@ymail.com");

        $userObject[] = $this->getUserObject(1, "test@ymail.com");
        $userObject[] = $this->getUserObject(2, "test2@ymail.com");

        $this->userRepositoryMock->method('getAll')->willThrowException(new \Exception('Database Access Exception'));

        $this->expectException(SystemException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage("Database Access Exception");

        $this->userService->getAllUser();
    }

    public function testShouldGetUserById()
    {
        $userObject = $this->getUserObject(1, "test@ymail.com");
        $user = $this->getUser(1, "test@ymail.com");

        $this->userRepositoryMock->expects($this->once())->method('get')->with(1)->willReturn($userObject);

        $result = $this->userService->getUserById(1);

        assertNotNull($result);
        assertEquals($user->getUsername(), $result->getUsername());
        assertEquals($user->getId(), $result->getId());
    }

    public function testShouldThrowExceptionForGetUserById()
    {

        $this->userRepositoryMock->method('get')->willThrowException(new \Exception('Database Access Exception'));

        $this->expectException(SystemException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage("Database Access Exception");

        $this->userService->getUserById(1);
    }

    public function testShouldDeleteUser()
    {
        $userObject = $this->getUserObject(1, 'test@ymail.com');

        $this->userRepositoryMock->expects($this->once())->method('get')->with(1)->willReturn($userObject);
        $this->userRepositoryMock->expects($this->once())->method('delete')->with(1);

        $this->userService->deleteUser(1);
    }

    public function testShouldThrowExceptionForDeleteUser()
    {
        $userObject = $this->getUserObject(1, 'test@ymail.com');

        $this->userRepositoryMock->expects($this->once())->method('get')->with(1)->willReturn($userObject);
        $this->userRepositoryMock->expects($this->once())->method('delete')->willThrowException(new \Exception('Database Access Exception'));

        $this->expectException(SystemException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage('Database Access Exception');

        $this->userService->deleteUser(1);
    }

    public function testShouldThrowExceptionForNullUser()
    {
        $this->userRepositoryMock->expects($this->once())->method('get')->with(1)->willReturn(null);

        $this->expectException(BusinessException::class);
        $this->expectExceptionCode(404);
        $this->expectExceptionMessage('User not found');

        $this->userService->deleteUser(1);
    }

    public function testShouldThrowExceptionForEmptyUserInGetUserById()
    {
        $this->userRepositoryMock->expects($this->once())->method('get')->with(1)->willReturn(null);

        $this->expectException(SystemException::class);
        $this->expectExceptionCode(500);

        $this->userService->getUserById(1);
    }
}

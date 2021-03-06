<?php
namespace App\Model;

class ToDo
{
    protected $database;

    public function __construct(\PDO $database)
    {
        $this->database = $database;
    }

    public function getAllToDos()
    {
        $statement = $this->database->prepare(
            'SELECT * FROM tasks ORDER BY id'
        );
        $statement->execute();
        return $statement->fetchAll();
    }

    public function getToDo($task_id)
    {
        $statement = $this->database->prepare(
            'SELECT * FROM tasks WHERE id=:id'
        );
        $statement->bindParam('id', $task_id);
        $statement->execute();
        return $statement->fetch();
    }
    
    public function createToDo($data)
    {
        $statement = $this->database->prepare(
            'INSERT INTO tasks (task,status,due_date,project,priority) VALUES (:task,:status,:due_date,:project,:priority)'
        );
        $statement->bindParam('task', $data['task']);
        $statement->bindParam('status', $data['status']);
        $statement->bindParam('due_date',$data['due_date']);
        $statement->bindParam('project',$data['project']);
        $statement->bindParam('priority',$data['priority']);
        $statement->execute();
        return $this->getToDo($this->database->lastInsertID());
    }
    
    public function updateToDo($data)
    {
        $statement = $this->database->prepare(
            'UPDATE tasks SET task=:task, status=:status, due_date=:due_date, project=:project, priority=:priority WHERE id=:id'
        );
        $statement->bindParam('task', $data['task']);
        $statement->bindParam('status', $data['status']);
        $statement->bindParam('id', $data['task_id']);
        $statement->bindParam('due_date',$data['due_date']);
        $statement->bindParam('project',$data['project']);
        $statement->bindParam('priority',$data['priority']);
        $statement->execute();
        return $this->getToDo($data['task_id']);
    }
    
    public function deleteToDo($task_id)
    {
        $statement = $this->database->prepare(
            'DELETE FROM tasks WHERE id=:id'
        );
        $statement->bindParam('id', $task_id);
        $statement->execute();
        return ['message' => 'task was deleted'];
    }
}
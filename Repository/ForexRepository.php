<?php

class Node {
    public $data;
    public $next;
    public $prev;

    public function __construct($data) {
        $this->data = $data;
        $this->next = null;
        $this->prev = null;
    }
}

class DoublyLinkedList {
    public $head;

    public function __construct() {
        $this->head = null;
    }

    public function isEmpty() {
        return $this->head === null;
    }

    public function addNode($data) {
        $newNode = new Node($data);
        if ($this->isEmpty()) {
            $this->head = $newNode;
        } else {
            $current = $this->head;
            while ($current->next !== null) {
                $current = $current->next;
            }
            $current->next = $newNode;
            $newNode->prev = $current;
        }
    }

    public function insertAfter($prevNodeData, $data) {
        $newNode = new Node($data);
        $current = $this->head;
        while ($current !== null && $current->data !== $prevNodeData) {
            $current = $current->next;
        }

        if ($current !== null) {
            $newNode->next = $current->next;
            $newNode->prev = $current;
            if ($current->next !== null) {
                $current->next->prev = $newNode;
            }
            $current->next = $newNode;
        } else {
            echo "Node dengan data $prevNodeData tidak ditemukan.\n";
        }
    }

    public function deleteNode($data) {
        $current = $this->head;
        while ($current !== null && $current->data !== $data) {
            $current = $current->next;
        }

        if ($current !== null) {
            if ($current->prev !== null) {
                $current->prev->next = $current->next;
            } else {
                $this->head = $current->next;
            }

            if ($current->next !== null) {
                $current->next->prev = $current->prev;
            }
            unset($current);
        } else {
            echo "Node dengan data $data tidak ditemukan.\n";
        }
    }

    public function searchNode($data) {
        $current = $this->head;
        while ($current !== null && $current->data !== $data) {
            $current = $current->next;
        }

        if ($current !== null) {
            echo "Node dengan data $data ditemukan.\n";
        } else {
            echo "Node dengan data $data tidak ditemukan.\n";
        }
    }

    public function displayForward() {
        $current = $this->head;
        while ($current !== null) {
            echo $current->data . " <-> ";
            $current = $current->next;
        }
        echo "null\n";
    }

    public function displayBackward() {
        $current = $this->head;
        while ($current->next !== null) {
            $current = $current->next;
        }

        while ($current !== null) {
            echo $current->data . " <-> ";
            $current = $current->prev;
        }
        echo "null\n";
    }

    public function bubbleSort() {
        $swapped = true;
        while ($swapped) {
            $swapped = false;
            $current = $this->head;
            while ($current->next !== null) {
                if ($current->data > $current->next->data) {
                    $this->swap($current, $current->next);
                    $swapped = true;
                }
                $current = $current->next;
            }
        }
    }

    public function swap($node1, $node2) {
        $temp = $node1->data;
        $node1->data = $node
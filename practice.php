<?php

// interface Vehicle{
// 	public function drive();
// }

// class Car implements Vehicle{
// 	public function drive(){
// 		echo 'the car is driving' . '\n';
// 	}
// }

// class Motorbike implements Vehicle{
// 	public function drive(){
// 		echo 'Motorbike is driving '. '\n';
// 	}
// }

// class VehicleType{
// 	const CAR='car';
// 	const MOTORBIKE='motorbike';
// }

// class VehicleFactory{
// 	public static function createVehicle($type){
// 		switch($type){
// 			case VehicleType::CAR:
// 				return new Car();
// 			case VehicleType::MOTORBIKE:
// 				return new Motorbike();
// 			default:
// 				throw new InvalidArgumentException('Please provide a suitable vehicle type ');
// 		}
// 	}
// }

// try{
// 	$car = VehicleFactory::createVehicle(VehicleType::CAR);
// 	$car->drive();
// 	$motorbike = VehicleFactory::createVehicle(VehicleType::MOTORBIKE);
// 	$motorbike->drive();
// }catch(Exception $e){
// 	echo $e->getMessage();
// }


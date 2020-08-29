Lendable Interview Test - Fee Calculation
=====
Implement `FeeCalculator` such that it fulfills the fee structure below.
The fee structure does not follow a formula. 

Values in between the breakpoints should be interpolated linearly between
the lower bound and upper bound that they fall between. The fee should then be 
"rounded up" such that (fee + loan amount) is an exact multiple of 5.

The minimum amount for a loan is £1,000, and the maximum is £20,000.
You can assume values will always be within this range but they may be any value 
up to 2 decimal places.
The term can be either 12 or 24 (number of months), you can also
assume values will always be within this set.

Provide a test suite verifying your solution, use any testing framework
you feel comfortable with. Use any libraries (or none) you feel add value 
to your solution.

Treat the packaged code as a template, if you feel that your solution can be
improved with modifications to it then please go ahead.

# Example
```php
<?php

use Lendable\Interview\Interpolation\Model\LoanApplication;

$calculator = new FeeCalculator(new LinearInterpolation(), new RoundUp());

$application = new LoanApplication(Term24::create(), Money::GBP(275000));
$calculator->calculate($application);
// $fee = Money::GBP(11500)
```

# Fee Structure
The fee structure doesn't follow particular algorithm and it is possible that same fee will be applicable for different amounts.

### Term 12
```
£1000: £50
£2000: £90
£3000: £90
£4000: £115
£5000: £100
£6000: £120
£7000: £140
£8000: £160
£9000: £180
£10000: £200
£11000: £220
£12000: £240
£13000: £260
£14000: £280
£15000: £300
£16000: £320
£17000: £340
£18000: £360
£19000: £380
£20000: £400
```

### Term 24

```
£1000: £70
£2000: £100
£3000: £120
£4000: £160
£5000: £200
£6000: £240
£7000: £280
£8000: £320
£9000: £360
£10000: £400
£11000: £440
£12000: £480
£13000: £520
£14000: £560
£15000: £600
£16000: £640
£17000: £680
£18000: £720
£19000: £760
£20000: £800
```

# Application
Application runs with PHP 7.4

#### Docker

```
cd docker
```

Build container
```
docker-compose build
```

Run container
```
docker-compose up -d
```

Enter into php container
```
docker-compose run php bash
```

#### Init Application

Enter into php container
```
docker-compose run php bash
```

install dependencies
```
make install
``` 

#### Tests

Application has been designed using a Test Driven Design approach and PHPUnit

Enter into php container
```
docker-compose run php bash
```

run tests
```
make test
``` 

#### Coding Standards

```
make cs
``` 

#### Static Code Analysis

```
make stan
``` 

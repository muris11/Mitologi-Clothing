import 'dart:async';
import 'dart:math';
import 'package:flutter/material.dart';

enum ParticleShape { rectangle, circle, strip, diamond, star, triangle, heart }

class ParticleShapeFactory {
  static Path getPath(ParticleShape shape, double size) {
    switch (shape) {
      case ParticleShape.rectangle:
        return Path()..addRect(
          Rect.fromCenter(center: Offset.zero, width: size, height: size * 1.5),
        );

      case ParticleShape.circle:
        return Path()
          ..addOval(Rect.fromCircle(center: Offset.zero, radius: size * 0.5));

      case ParticleShape.strip:
        return Path()
          ..moveTo(-size * 0.3, -size)
          ..lineTo(size * 0.3, -size)
          ..lineTo(size * 0.2, size)
          ..lineTo(-size * 0.2, size)
          ..close();

      case ParticleShape.diamond:
        return Path()
          ..moveTo(0, -size)
          ..lineTo(size * 0.7, 0)
          ..lineTo(0, size)
          ..lineTo(-size * 0.7, 0)
          ..close();

      case ParticleShape.star:
        final path = Path();
        const points = 5;
        final innerRadius = size * 0.4;
        final outerRadius = size;

        for (var i = 0; i < points * 2; i++) {
          final radius = i.isEven ? outerRadius : innerRadius;
          final angle = i * pi / points;
          final dx = cos(angle) * radius;
          final dy = sin(angle) * radius;

          if (i == 0) {
            path.moveTo(dx, dy);
          } else {
            path.lineTo(dx, dy);
          }
        }
        return path..close();

      case ParticleShape.triangle:
        return Path()
          ..moveTo(0, -size)
          ..lineTo(size * 0.866, size * 0.5)
          ..lineTo(-size * 0.866, size * 0.5)
          ..close();

      case ParticleShape.heart:
        final path = Path();
        path.moveTo(0, size * 0.3);

        // Left curve
        path.cubicTo(-size * 0.5, -size * 0.4, -size, 0, 0, size);

        // Right curve
        path.cubicTo(size, 0, size * 0.5, -size * 0.4, 0, size * 0.3);

        return path;
    }
  }
}

enum ParticleState { launch, burst, float, fall }

class EnhancedConfettiParticle {
  Offset position;
  Offset velocity;
  final Color color;
  double rotation = 0;
  double rotationSpeed;
  double rotationAcceleration;
  final double size;
  final ParticleShape shape;

  final double mass;
  final double turbulence;
  final double flutterSpeed;
  final double flutterAmount;
  double lifespan;
  ParticleState state;

  late Offset controlPoint1;
  late Offset controlPoint2;

  double stateTime = 0;

  EnhancedConfettiParticle({
    required this.position,
    required this.velocity,
    required this.color,
    required this.shape,
    required this.size,
  }) : mass = 0.8 + Random().nextDouble() * 0.4,
       turbulence = Random().nextDouble() * 0.6,
       flutterSpeed = 2 + Random().nextDouble() * 3,
       flutterAmount = Random().nextDouble() * 2.5,
       rotationSpeed = Random().nextDouble() * 0.15 - 0.075,
       rotationAcceleration = Random().nextDouble() * 0.01 - 0.005,
       lifespan = 1.0,
       state = ParticleState.launch {
    _initializeBezierPoints();
  }

  void _initializeBezierPoints() {
    final random = Random();
    controlPoint1 = Offset(
      position.dx + (random.nextDouble() - 0.5) * 100,
      position.dy - random.nextDouble() * 50,
    );
    controlPoint2 = Offset(
      position.dx + (random.nextDouble() - 0.5) * 200,
      position.dy - random.nextDouble() * 100,
    );
  }
}

class EnhancedConfettiOptions {
  final int particleCount;
  final double initialSpread;
  final double burstSpread;
  final double baseVelocity;
  final double burstVelocity;
  final double gravity;
  final double airResistance;
  final double turbulenceFactor;
  final List<Color> colors;
  final List<ParticleShape> shapes;
  final double launchDuration;
  final double burstDuration;
  final double floatDuration;

  const EnhancedConfettiOptions({
    this.particleCount = 100,
    this.initialSpread = 15,
    this.burstSpread = 120,
    this.baseVelocity = -8.0,
    this.burstVelocity = -15.0,
    this.gravity = 0.25,
    this.airResistance = 0.02,
    this.turbulenceFactor = 0.5,
    this.colors = const [
      Colors.red,
      Colors.blue,
      Colors.green,
      Colors.yellow,
      Colors.purple,
      Colors.orange,
    ],
    this.shapes = const [
      ParticleShape.rectangle,
      ParticleShape.circle,
      ParticleShape.strip,
      ParticleShape.diamond,
      ParticleShape.star,
    ],
    this.launchDuration = 0.2, // 200ms
    this.burstDuration = 0.3, // 300ms
    this.floatDuration = 1.5, // 1.5s
  });
}

class EnhancedConfettiPainter extends CustomPainter {
  final List<EnhancedConfettiParticle> particles;
  final double progress;
  final EnhancedConfettiOptions options;
  final Random random = Random();

  final List<double> perlinSeed = List.generate(
    1000,
    (index) => Random().nextDouble() * 2 - 1,
  );

  EnhancedConfettiPainter({
    required this.particles,
    required this.progress,
    required this.options,
  });

  double _perlinNoise(double x) {
    final i = x.floor() % perlinSeed.length;
    final j = (i + 1) % perlinSeed.length;
    final t = x - x.floor();
    final smoothT = t * t * (3 - 2 * t);
    return perlinSeed[i] + (perlinSeed[j] - perlinSeed[i]) * smoothT;
  }

  void _applyLaunchPhysics(EnhancedConfettiParticle particle) {
    final normalizedTime = particle.stateTime / options.launchDuration;
    particle.velocity = Offset(
      particle.velocity.dx * (1 - options.airResistance),
      options.baseVelocity * (1 - normalizedTime * 0.5),
    );

    particle.velocity += Offset(
      _perlinNoise(particle.stateTime * 5) * 0.5,
      _perlinNoise(particle.stateTime * 5 + 100) * 0.5,
    );
  }

  void _applyBurstPhysics(EnhancedConfettiParticle particle) {
    final normalizedTime = particle.stateTime / options.burstDuration;
    final angle = random.nextDouble() * pi * 2;
    final speed = options.burstVelocity * (1 - normalizedTime);

    particle.velocity = Offset(
      cos(angle) * speed * (1 + random.nextDouble()),
      sin(angle) * speed + options.baseVelocity,
    );

    final turbulence = options.turbulenceFactor * (1 - normalizedTime);
    particle.velocity += Offset(
      _perlinNoise(particle.stateTime * 3) * turbulence,
      _perlinNoise(particle.stateTime * 3 + 50) * turbulence,
    );
  }

  void _applyFloatPhysics(EnhancedConfettiParticle particle) {
    final normalizedTime = particle.stateTime / options.floatDuration;
    final gravityEffect = options.gravity * normalizedTime;

    particle.velocity = Offset(
      particle.velocity.dx * (1 - options.airResistance),
      particle.velocity.dy * (1 - options.airResistance) + gravityEffect,
    );

    final turbulenceStrength = options.turbulenceFactor * (1 - normalizedTime);
    particle.velocity += Offset(
      _perlinNoise(particle.stateTime * 2) * turbulenceStrength,
      _perlinNoise(particle.stateTime * 2 + 100) * turbulenceStrength * 0.5,
    );
  }

  void _applyFallPhysics(EnhancedConfettiParticle particle) {
    particle.velocity = Offset(
      particle.velocity.dx * (1 - options.airResistance * 0.5),
      particle.velocity.dy + options.gravity * particle.mass,
    );

    final drift = _perlinNoise(particle.stateTime) * 0.3;
    particle.velocity += Offset(drift, 0);
  }

  void _applyRotation(EnhancedConfettiParticle particle) {
    double rotationMultiplier = 1.0;

    switch (particle.state) {
      case ParticleState.launch:
        rotationMultiplier = 0.5;
        break;
      case ParticleState.burst:
        rotationMultiplier = 2.0;
        break;
      case ParticleState.float:
        rotationMultiplier = 1.0;
        break;
      case ParticleState.fall:
        rotationMultiplier = 0.7;
        break;
    }

    particle.rotationSpeed += particle.rotationAcceleration;
    particle.rotation += particle.rotationSpeed * rotationMultiplier;

    final velocityMagnitude = particle.velocity.distance;
    particle.rotation += sin(particle.stateTime * 5) * velocityMagnitude * 0.01;
  }

  void _applyFlutter(EnhancedConfettiParticle particle) {
    double flutterStrength;

    switch (particle.state) {
      case ParticleState.launch:
        flutterStrength = 0.2;
        break;
      case ParticleState.burst:
        flutterStrength = 1.0;
        break;
      case ParticleState.float:
        flutterStrength = 0.8;
        break;
      case ParticleState.fall:
        flutterStrength = 0.4;
        break;
    }

    final flutter =
        _perlinNoise(particle.stateTime * particle.flutterSpeed) *
        particle.flutterAmount *
        flutterStrength;

    particle.velocity += Offset(flutter, 0);
  }

  void _updatePosition(EnhancedConfettiParticle particle, Size size) {
    particle.position += particle.velocity;

    final normalizedTime = particle.stateTime.clamp(0.0, 1.0);
    if (particle.state != ParticleState.fall) {
      final bezierPoint = _calculateBezierPoint(
        normalizedTime,
        particle.position,
        particle.controlPoint1,
        particle.controlPoint2,
        particle.position + Offset(0, size.height * 0.5),
      );
      particle.position = particle.position * 0.9 + bezierPoint * 0.1;
    }
  }

  Offset _calculateBezierPoint(
    double t,
    Offset p0,
    Offset p1,
    Offset p2,
    Offset p3,
  ) {
    final u = 1 - t;
    final tt = t * t;
    final uu = u * u;
    final uuu = uu * u;
    final ttt = tt * t;

    return Offset(
      uuu * p0.dx + 3 * uu * t * p1.dx + 3 * u * tt * p2.dx + ttt * p3.dx,
      uuu * p0.dy + 3 * uu * t * p1.dy + 3 * u * tt * p2.dy + ttt * p3.dy,
    );
  }

  double _calculateOpacity(EnhancedConfettiParticle particle) {
    double baseOpacity;

    switch (particle.state) {
      case ParticleState.launch:
        baseOpacity = particle.stateTime / options.launchDuration;
        break;
      case ParticleState.burst:
        baseOpacity = 1.0;
        break;
      case ParticleState.float:
        baseOpacity = 1.0;
        break;
      case ParticleState.fall:
        baseOpacity = 1.0 - (particle.stateTime / 2.0).clamp(0.0, 1.0);
        break;
    }

    final flicker = 0.9 + _perlinNoise(particle.stateTime * 10) * 0.1;
    return (baseOpacity * flicker).clamp(0.0, 1.0);
  }

  void _drawParticleShape(
    Canvas canvas,
    Paint paint,
    EnhancedConfettiParticle particle,
  ) {
    final path = ParticleShapeFactory.getPath(particle.shape, particle.size);

    switch (particle.state) {
      case ParticleState.launch:
        canvas.scale(1.0, 1.0 + particle.velocity.distance * 0.02);
        break;
      case ParticleState.burst:
        final scaleFactor = 1.0 + sin(particle.stateTime * 10) * 0.1;
        canvas.scale(scaleFactor, scaleFactor);
        break;
      case ParticleState.float:
        final pulseFactor = 1.0 + sin(particle.stateTime * 3) * 0.05;
        canvas.scale(pulseFactor, pulseFactor);
        break;
      case ParticleState.fall:
        break;
    }

    canvas.drawPath(path, paint);

    if (particle.state == ParticleState.burst) {
      paint.color = paint.color.withValues(alpha: 0.3);
      canvas.drawPath(
        path,
        paint
          ..style = PaintingStyle.stroke
          ..strokeWidth = 1,
      );
    }
  }

  @override
  void paint(Canvas canvas, Size size) {
    final paint = Paint()..style = PaintingStyle.fill;

    for (var particle in particles) {
      _updateParticleState(particle, size);
      _updateParticlePhysics(particle, size);
      _renderParticle(canvas, paint, particle, size);
    }
  }

  void _updateParticleState(EnhancedConfettiParticle particle, Size size) {
    particle.stateTime += 0.016; // Assuming 60fps

    if (particle.state == ParticleState.launch &&
        particle.stateTime >= options.launchDuration) {
      particle.state = ParticleState.burst;
      particle.stateTime = 0;
    } else if (particle.state == ParticleState.burst &&
        particle.stateTime >= options.burstDuration) {
      particle.state = ParticleState.float;
      particle.stateTime = 0;
    } else if (particle.state == ParticleState.float &&
        particle.stateTime >= options.floatDuration) {
      particle.state = ParticleState.fall;
      particle.stateTime = 0;
    }
  }

  void _updateParticlePhysics(EnhancedConfettiParticle particle, Size size) {
    switch (particle.state) {
      case ParticleState.launch:
        _applyLaunchPhysics(particle);
        break;
      case ParticleState.burst:
        _applyBurstPhysics(particle);
        break;
      case ParticleState.float:
        _applyFloatPhysics(particle);
        break;
      case ParticleState.fall:
        _applyFallPhysics(particle);
        break;
    }

    _applyRotation(particle);
    _applyFlutter(particle);
    _updatePosition(particle, size);
  }

  void _renderParticle(
    Canvas canvas,
    Paint paint,
    EnhancedConfettiParticle particle,
    Size size,
  ) {
    final opacity = _calculateOpacity(particle);
    paint.color = particle.color.withValues(alpha: opacity);

    canvas.save();
    canvas.translate(
      size.width * 0.5 + particle.position.dx,
      size.height * 0.5 + particle.position.dy,
    );
    canvas.rotate(particle.rotation);

    if (opacity > 0.3) {
      paint.maskFilter = const MaskFilter.blur(BlurStyle.normal, 0.5);
    }

    _drawParticleShape(canvas, paint, particle);
    canvas.restore();
  }

  @override
  bool shouldRepaint(covariant CustomPainter oldDelegate) {
    return true;
  }
}

class CelebrateEffect extends StatefulWidget {
  final Widget child;
  final bool autoPlay;
  final Duration duration;

  const CelebrateEffect({
    super.key,
    required this.child,
    this.autoPlay = true,
    this.duration = const Duration(seconds: 4),
  });

  @override
  State<CelebrateEffect> createState() => _CelebrateEffectState();
}

class _CelebrateEffectState extends State<CelebrateEffect>
    with SingleTickerProviderStateMixin {
  late AnimationController _controller;
  List<EnhancedConfettiParticle> _particles = [];
  Timer? _timer;
  bool _isPlaying = false;

  final EnhancedConfettiOptions _celebrationConfig =
      const EnhancedConfettiOptions(
        particleCount: 150,
        shapes: [
          ParticleShape.rectangle,
          ParticleShape.circle,
          ParticleShape.strip,
          ParticleShape.diamond,
          ParticleShape.star,
        ],
        initialSpread: 20,
        burstSpread: 150,
        burstVelocity: -12.0,
        turbulenceFactor: 0.7,
      );

  @override
  void initState() {
    super.initState();
    _controller = AnimationController(vsync: this, duration: widget.duration)
      ..addListener(() {
        if (mounted) {
          setState(() {});
        }
      });

    if (widget.autoPlay) {
      WidgetsBinding.instance.addPostFrameCallback((_) {
        _startConfetti(_celebrationConfig);
      });
    }
  }

  @override
  void dispose() {
    _controller.dispose();
    _timer?.cancel();
    super.dispose();
  }

  void _startConfetti(EnhancedConfettiOptions options) {
    if (_isPlaying) return;

    setState(() {
      _isPlaying = true;
      _particles = List.generate(
        options.particleCount,
        (index) => EnhancedConfettiParticle(
          position: const Offset(0, 0),
          velocity: const Offset(0, 0),
          color: options.colors[index % options.colors.length],
          shape: options.shapes[index % options.shapes.length],
          size: 8 + index % 4 * 2,
        ),
      );
    });

    _controller.forward(from: 0);

    _timer?.cancel();
    _timer = Timer(widget.duration, () {
      if (mounted) {
        setState(() {
          _isPlaying = false;
          _particles.clear();
        });
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    return Stack(
      children: [
        widget.child,
        if (_isPlaying)
          Positioned.fill(
            child: IgnorePointer(
              child: CustomPaint(
                painter: EnhancedConfettiPainter(
                  particles: _particles,
                  progress: _controller.value,
                  options: _celebrationConfig,
                ),
              ),
            ),
          ),
      ],
    );
  }
}
